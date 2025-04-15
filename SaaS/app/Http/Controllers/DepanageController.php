<?php

namespace App\Http\Controllers;

use App\Models\Depannage;
use App\Models\Type;
use Illuminate\Http\Request;

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
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filtrer par nom
        if ($request->filled('nom')) {
            $query->where('nom', 'like', '%' . $request->nom . '%');
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
        $depannages = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('dashboard', compact('depannages'));
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
        // Validation des données envoyées
        $request->validate([
            'statut' => 'required|string|in:À planifier,Affecter,Approvisionnement,À facturer',
        ]);

        // Récupérer le depannage
        $depannage = Depannage::findOrFail($id);
        $ancienStatut = $depannage->statut;

        // Mettre à jour le statut
        $nouveauStatut = $request->input('statut');
        $depannage->statut = $nouveauStatut;
        $depannage->save();

        if($ancienStatut != 'Approvisionnement' && $nouveauStatut == 'Approvisionnement') {
            // Créer un nouvel enregistrement dans la table 'approvisionnement'
            $depannage->approvisionnements()->create([
                'statut' => 'À planifier',
            ]);
        }
        if($ancienStatut != 'À facturer' && $nouveauStatut == 'À facturer') {
            // Créer un nouvel enregistrement dans la table 'facturation'
            $depannage->facturations()->create([
                'montant' => 0,
                'statut' => 'Non envoyée',
            ]);
        }

        return response()->json(['message' => 'Statut mis à jour avec succès!']);
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
                'demande_type' => 'required|string|in:portail,portillon,barrière',
                'panne' => 'required|string',
                'elec' => 'nullable|string',
                'infos' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
            ]);

            // Création du Depannage
            $depannage = Depannage::create([
                'nom' => $request->input('name'),
                'adresse' => $request->input('add'),
                'contact_email' => $request->input('email'),
                'description_probleme' => $request->input('panne'),
                'statut' => 'À planifier',
                'telephone' => $request->input('tel'),
                'type_materiel' => $request->input('demande_type'),
                'message_erreur' => $request->input('elec'),
                'infos_supplementaires' => $request->input('infos'),
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

            } else {
                return redirect()->route('confirmation.page')->with('success', 'Votre demande a été enregistrée !');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande.' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $depannage = Depannage::findOrFail($id);
        return view('depannage.show', compact('depannage'));
    }
}
