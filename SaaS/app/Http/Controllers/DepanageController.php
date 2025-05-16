<?php

namespace App\Http\Controllers;

use App\Models\Depannage;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Termwind\Components\Element;

class DepanageController extends Controller
{
    public function index(Request $request)
    {
        // Commencer la requête de base
        $query = Depannage::with('historiques', 'types');

        // Filtrer par statut
        if ($request->filled('statut') && $request->statut !== 'all') {
            $query->where('statut', $request->statut);
        }

        // Filtrer par date (ex: date de création)
        if ($request->filled('date_min')) {
            $query->whereDate('created_at', '>=', $request->date_min);
        }

        if ($request->filled('date_max')) {
            $query->whereDate('created_at', '<=', $request->date_max);
        }

        // Filtrer par nom
        if ($request->filled('nom')) {
            $query->where('nom', 'like', '%' . $request->nom . '%');
        }

        // Filtre par code postal
        if ($request->filled('code_postal')) {
            $cp = $request->code_postal;

            if (preg_match('/^(\d{2})0+$/', $cp, $matches)) {
                $prefix = $matches[1];
                $query->where('code_postal', 'like', $prefix . '%');
            } elseif (strlen($cp) === 2) {
                $query->where('code_postal', 'like', $cp . '%');
            } else {
                $query->where('code_postal', $cp);
            }
        }

        // Filtrer par lieu (adresse ici ?)
        if ($request->filled('lieu')) {
            $query->where('adresse', 'like', '%' . $request->lieu . '%');
        }

        // Filtrer par type (garantie / contrat)
        if ($request->filled('garantie')) {
            $garantieMap = [
                'oui' => 'Avec garantie',
                'non' => 'Sans garantie',
            ];

            if (array_key_exists($request->garantie, $garantieMap)) {
                $query->whereHas('types', function ($q) use ($request, $garantieMap) {
                    $q->where('garantie', $garantieMap[$request->garantie]);
                });
            }
        }
        if ($request->filled('contrat')) {
            $contratMap = [
                'oui' => 'Contrat de maintenance',
                'non' => 'Sans contrat',
            ];

            if (array_key_exists($request->contrat, $contratMap)) {
                $query->whereHas('types', function ($q) use ($request, $contratMap) {
                    $q->where('contrat', $contratMap[$request->contrat]);
                });
            }
        }

        // Appliquer le tri avant de récupérer les résultats
        $depannages = $query->where('archived', '=', false)->orderBy('created_at', 'desc')->get();
        $techniciens = User::where('role', 'technicien')->get();

        return view('admin/dashboard', compact('depannages', 'techniciens'));
    }

    public function destroy($id)
    {
        // Suppression du dépannage
        $depannage = Depannage::findOrFail($id);
        $depannage->delete();

        // Suppression du type associé
        $type = Type::where('depannage_id', $id)->first();
        if ($type) {
            $type->delete();
        }

        return response()->json(['message' => 'Dépannage supprimé avec succès!']);
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            // Validation des données envoyées
            $request->validate([
                'statut' => 'required|string|in:À planifier,Affecter,Approvisionnement,À facturer',
            ]);

            // Récupérer le dépannage
            $depannage = Depannage::findOrFail($id);
            $ancienStatut = $depannage->statut;

            // Mettre à jour le statut
            $nouveauStatut = $request->input('statut');
            $depannage->statut = $nouveauStatut;

            // Si le statut est "Affecter"
            if ($nouveauStatut == 'Affecter') {
                $depannage->save();
                // Vérifier si la date est déjà renseignée
                if ($depannage->depannage_date == null) {
                    // Si la date est null, demander à l'utilisateur de renseigner une date
                    return response()->json(['action' => 'request_date']);
                } else {
                    // Si la date est déjà renseignée, demander à l'utilisateur s'il souhaite la modifier
                    return response()->json(['action' => 'modify_date', 'date' => $depannage->depannage_date]);
                }
            }

            // Si le statut passe de "Approvisionnement" à un autre statut
            if ($ancienStatut != 'Approvisionnement' && $nouveauStatut == 'Approvisionnement') {
                $depannage->save();
                // Créer un nouvel enregistrement dans la table 'approvisionnement'
                $depannage->approvisionnements()->create([
                    'statut' => 'À planifier',
                ]);
            }

            // Si le statut passe de "À facturer" à un autre statut
            if ($ancienStatut != 'À facturer' && $nouveauStatut == 'À facturer') {
                // Créer un nouvel enregistrement dans la table 'facturation'
                if ($depannage->date_depannage == null) {
                    return response()->json(['message' => 'Aucune date d\'intervenion'], 500);
                } else {
                    $depannage->save();
                    $depannage->facturations()->create([
                        'montant' => 0,
                        'statut' => 'Non envoyée',
                        'date_intervention' => $depannage->date_depannage,
                    ]);
                }
            }

            $depannage->save();
            return response()->json(['message' => 'Statut mis à jour avec succès!']);
        } catch (QueryException $e) {
            Log::error("Erreur SQL dans updateStatus : " . $e->getMessage());
            return response()->json(['message' => 'Erreur de base de données.'], 500);
        } catch (\Throwable $e) {
            Log::error("Erreur générale dans updateStatus : " . $e->getMessage());
            return response()->json(['message' => 'Erreur interne du serveur.'], 500);
        }
    }

    public function updateDate(Request $request, $id)
    {
        $request->validate([
            'date_depannage' => 'required|date',
        ]);
        $depannage = Depannage::find($id);
        if (!$depannage) {
            return response()->json(['error' => 'Dépannage non trouvé.'], 404);
        }
        $depannage->date_depannage = $request->date_depannage;
        $depannage->save();

        return response()->json(['success' => 'Statut et date mise à jour avec succès.']);
    }

    // méthode store pour enregistrer un dépannage
    public function store(Request $request)
    {
        try {

            $formSource = $request->input('form_source');
            // Validation des données
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'tel' => 'required|string|max:20',
                'add' => 'required|string|max:255',
                'add-code-postal' => 'required|string|max:10',
                'demande_type' => 'required|string|in:portail,portillon,barrière,tourniquet',
                'panne' => 'required|string',
                'elec' => 'nullable|string',
                'infos' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
                'date_intervention' => 'date',
            ]);

            $provenance = 'ajout manuel';
            if($formSource == 'formulaire_ca'){
                $provenance = 'chargé d\'affaire';
            } elseif ($formSource == 'formulaire_classique') {
                $provenance = 'client';
            }

            // Création du Depannage
            $depannage = Depannage::create([
                'nom' => $request->input('name'),
                'adresse' => $request->input('add'),
                'code_postal' => $request->input('add-code-postal'),
                'contact_email' => $request->input('email'),
                'description_probleme' => $request->input('panne'),
                'statut' => 'À planifier',
                'telephone' => $request->input('tel'),
                'type_materiel' => $request->input('demande_type'),
                'message_erreur' => $request->input('elec'),
                'infos_supplementaires' => $request->input('infos'),
                'provenance' => $provenance,
                'date_depannage' => $request->input('date_intervention'),
            ]);

            // Création du Type associé au Depannage
            $type = new Type([
                'depannage_id' => $depannage->id,
                'garantie' => 'Non renseigné',
                'contrat' => 'Non renseigné',
            ]);
            $depannage->types()->save($type);

            // Gestion des images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $imageName = time() . '_' . $image->getClientOriginalName();
                        $image->move(public_path('images'), $imageName);

                        $depannage->photos()->create([
                            'chemin_photo' => $imageName,
                        ]);
                    }
                }
            }

            if($formSource == 'formulaire_ca'){
                return redirect()->route('caconfirmation.page')->with('success', 'Votre demande a été enregistrée !');
            } else if ($formSource == 'formulaire_classique'){
                return redirect()->route('confirmation.page')->with('success', 'Votre demande a été enregistrée !');
            } else {
                return redirect()->route('admin.dashboard')->with('success', 'ajout du dépannage effectué avec succès !');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande.' . $e->getMessage());
        }
    }

    public function archiver(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:depannages,id',
        ]);

        $depannage = Depannage::findOrFail($request->id);
        $depannage->archived = true;
        $depannage->save();

        return response()->json(['message' => 'Dépannage archivé avec succès!']);
    }

    public function desarchiver(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:depannages,id',
        ]);

        $depannage = Depannage::findOrFail($request->id);
        $depannage->archived = false;
        $depannage->save();

        return response()->json(['message' => 'Dépannage désarchivé avec succès!']);
    }

    public function show($id)
    {
        $depannage = Depannage::findOrFail($id);
        $users = User::all()->where('role', '=', 'technicien');
        return view('admin.depannage.show', compact('depannage', 'users'));
    }
}
