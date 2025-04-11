<?php

namespace App\Http\Controllers;

use App\Models\Depannage;
use App\Models\Type;
use Illuminate\Http\Request;

class DepanageController extends Controller
{
    public function index()
    {
        $depannages = Depannage::with('historiques', 'types')->get();

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
                'statut' => 'Non payé',
            ]);
        }

        return response()->json(['message' => 'Statut mis à jour avec succès!']);
    }

    // méthode store pour enregistrer un dépannage
    public function store(Request $request)
    {
        try {
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

            return redirect()->route('confirmation.page')->with('success', 'Votre demande a été enregistrée !');
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
