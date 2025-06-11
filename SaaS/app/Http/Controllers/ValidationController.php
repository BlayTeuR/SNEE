<?php

namespace App\Http\Controllers;

use App\Models\Depannage;
use App\Models\Entretien;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

            $jourCourantActive = $type === 'depannage' && $request->input('jour_courant', 'on') === 'on';

            if ($request->filled('nom')) {
                $query->where('nom', 'like', '%' . $request->input('nom') . '%');
            }

            if ($request->filled('date')) {
                $query->whereDate('date_depannage', '=', $request->input('date'));
            }

            if ($jourCourantActive) {
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
        } else if ($type === 'entretiens') {
            $query = Entretien::with(['historiques', 'validations'])
                ->where('archived', false);

            // Filtres généraux
            if ($request->filled('nom')) {
                $query->where('nom', 'like', '%' . $request->input('nom') . '%');
            }
            if ($request->filled('date')) {
                $query->whereDate('derniere_date', $request->input('date'));
            }

            $moisCourantActive = $request->input('mois_courant', 'on') === 'on';

            if ($moisCourantActive) {
                // Filtrer sur les entretiens du mois courant soit via derniere_date soit via validation->date
                $query->where(function ($q) {
                    $q->whereMonth('derniere_date', now()->month)
                        ->whereYear('derniere_date', now()->year)
                        ->orWhereHas('validations', function ($sub) {
                            $sub->whereMonth('date', now()->month)
                                ->whereYear('date', now()->year);
                        });
                });
            }

            $entretiens = $query->get();

            $interventions = collect();

            foreach ($entretiens as $entretien) {
                // 1) Ajouter tous les historiques (dates) d'interventions (validation ou non)
                foreach ($entretien->historiques as $historique) {
                    $date = \Carbon\Carbon::parse($historique->date)->format('Y-m-d');

                    if (!$moisCourantActive || \Carbon\Carbon::parse($date)->isSameMonth(now())) {
                        $interventions->push([
                            'entretien' => $entretien,
                            'date' => $date,
                        ]);
                    }
                }

                // 2) Ajouter la date principale d'entretien (derniere_date) si elle n'est pas déjà dans la liste
                if ($entretien->derniere_date) {
                    $datePlanifiee = \Carbon\Carbon::parse($entretien->derniere_date)->format('Y-m-d');

                    $dejaPresente = $interventions->contains(function ($i) use ($datePlanifiee, $entretien) {
                        return $i['date'] === $datePlanifiee && $i['entretien']->id === $entretien->id;
                    });

                    if (!$dejaPresente && (!$moisCourantActive || \Carbon\Carbon::parse($datePlanifiee)->isSameMonth(now()))) {
                        $interventions->push([
                            'entretien' => $entretien,
                            'date' => $datePlanifiee,
                        ]);
                    }
                }
            }
        }

        $interventions = $interventions->sortByDesc('date')->values();

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

    public function valideEntretien(Request $request, $id)
    {
        try {
            // Validation avec règle conditionnelle pour 'date'
            $validated = $request->validate([
                'intervention_id' => 'required|integer',
                'option' => 'required|in:nouvelle_date,valide',
                'type' => 'required|in:depannage,entretiens',
                'date' => 'nullable|date|required_if:option,nouvelle_date',
                'context' => 'required|in:nonValide,valide',
                'commentaire' => 'nullable|string',
            ]);

            if ($validated['type'] === 'entretiens') {
                $intervention = Entretien::findOrFail($id);
                $date_before = $intervention->derniere_date;

                if ($validated['option'] === 'valide') {
                    // On reprogramme 6 mois après la dernière date
                    $intervention->derniere_date = Carbon::parse($date_before)->addMonths(6)->format('Y-m-d');
                } elseif ($validated['option'] === 'nouvelle_date') {
                    $intervention->derniere_date = Carbon::parse($validated['date'])->format('Y-m-d');
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

            return response()->json(['success' => true, 'message' => 'Entretien validé/reprogrammé.']);
        } catch (\Throwable $e) {
            \Log::error('Erreur valideEntretien', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ], 500);
        }
    }
}
