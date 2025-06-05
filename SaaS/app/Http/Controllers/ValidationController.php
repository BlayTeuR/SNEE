<?php

namespace App\Http\Controllers;

use App\Models\Depannage;
use App\Models\Entretien;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'depannage');
        $interventions = collect();

        if ($type === 'depannage') {
            $query = Depannage::with(['historiques', 'validations', 'types'])
                ->where(function ($q) {
                    $q->where('statut', 'Affecter')
                        ->orWhereHas('validations', function ($query) {
                            $query->whereIn('validation', ['valide', 'nonvalide']);
                        });
                })
                ->where('archived', false);

            if ($request->filled('nom')) {
                $query->where('nom', 'like', '%' . $request->input('nom') . '%');
            }

            if ($request->filled('date')) {
                $query->whereDate('date_depannage', '=', $request->input('date'));
            }

            if ($request->input('jour_courant', 'on') === 'on') {
                $query->where(function ($q) {
                    $q->whereDate('date_depannage', now()->format('Y-m-d'))
                        ->orWhereHas('validations', function ($sub) {
                            $sub->whereDate('date', now()->format('Y-m-d'));
                        });
                });
            }

            $depannages = $query->get();

            $jourCourantActive = $request->input('jour_courant', 'on') === 'on';
            $interventions = collect();

            foreach ($depannages as $depannage) {
                foreach ($depannage->historiques as $historique) {
                    $date = \Carbon\Carbon::parse($historique->date)->format('Y-m-d');

                    if (!$jourCourantActive || $date === now()->format('Y-m-d')) {
                        $interventions->push([
                            'depannage' => $depannage,
                            'date' => $date,
                        ]);
                    }
                }

                // Ajouter l'intervention planifiée si elle est valide
                if ($depannage->date_depannage) {
                    $datePlanifiee = \Carbon\Carbon::parse($depannage->date_depannage)->format('Y-m-d');

                    $dejaPresente = $interventions->contains(function ($i) use ($datePlanifiee, $depannage) {
                        return $i['date'] === $datePlanifiee && $i['depannage']->id === $depannage->id;
                    });

                    if (!$dejaPresente && (!$jourCourantActive || $datePlanifiee === now()->format('Y-m-d'))) {
                        $interventions->push([
                            'depannage' => $depannage,
                            'date' => $datePlanifiee,
                        ]);
                    }
                }
            }


            // Trier par date décroissante
            $interventions = $interventions->sortByDesc('date')->values();
            // dd($interventions);
        } else {
            // Tu peux ajouter un traitement similaire pour les entretiens si besoin
            $entretiens = Entretien::with('historiques')->get();
            return view('admin.validation', compact('entretiens', 'type'));
        }

        return view('admin.validation', compact('interventions', 'type'))
            ->with('success', 'Données récupérées avec succès.');
    }

    public function validationDepannage(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'intervention_id' => 'required|integer',
                'option' => 'required|in:nouvelle_date,ultérieurement,approvisionnement,facturer',
                'type' => 'required|in:depannage,entretiens',
                'date' => 'nullable|date',
                'context' => 'required|in:nonValide,valide',
                'commentaire' => 'nullable|string',
            ]);

            if ($validated['type'] === 'depannage') {
                $intervention = Depannage::findOrFail($id); // ← ici on récupère le modèle avec l'ID passé
                $date_before = $intervention->date_depannage;

                if ($validated['option'] === 'ultérieurement') {
                    $intervention->statut = 'À planifier';
                    $intervention->date_depannage = null;
                } elseif ($validated['option'] === 'nouvelle_date') {
                    $intervention->statut = 'Affecter';
                    $intervention->date_depannage = \Carbon\Carbon::parse($validated['date'])->format('Y-m-d');
                } elseif ($validated['option'] === 'approvisionnement') {
                    $intervention->statut = 'Approvisionnement';
                    $intervention->approvisionnements()->create([
                        'statut' => 'À planifier',
                    ]);
                    $intervention->date_depannage = null;
                } elseif ($validated['option'] === 'facturer') {
                    $intervention->statut = 'À facturer';
                    $intervention->facturations()->create([
                        'montant' => 0,
                        'statut' => 'Non envoyée',
                        'date_intervention' => $intervention->date_depannage,
                    ]);
                }

                $intervention->save();

                $intervention->validations()->create([
                    'validation' => $validated['context'],
                    'commentaire' => $validated['commentaire'] ?? null,
                    'date' => $date_before,
                    'detail' => $validated['option'],
                ]);

                $intervention->historiques()->firstOrCreate([
                    'date' => $date_before,
                ]);

            }

            return response()->json(['message' => 'Intervention mise à jour.']);
        } catch (\Throwable $e) {
            \Log::error('Erreur replanifierWithoutHisto', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ], 500);
        }
    }

    public function valideEntretien(Request $request){
        $validated = $request->validate([]);
    }

}
