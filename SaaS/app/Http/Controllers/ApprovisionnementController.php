<?php

namespace App\Http\Controllers;

use App\Models\Approvisionnement;
use Illuminate\Http\Request;

class ApprovisionnementController extends Controller
{
    public function index(){
        $approvisionnements = Approvisionnement::with('depannage', 'pieces')->get();

        return view('approvisionnement', compact('approvisionnements'));
    }

    public function updateStatus(Request $request, $id){

        $request->validate([
            'statut' => 'required|string|in:À planifier,En attente,Fait',
        ]);

        $approvisionnement = Approvisionnement::findOrFail($id);

        // Mettre à jour le statut
        $nouveauStatut = $request->input('statut');
        $approvisionnement->statut = $nouveauStatut;
        $approvisionnement->save();

        return response()->json(['message' => 'Statut mis à jour avec succès!']);
    }

    public function destroy($id)
    {
        // Suppression de l'approvisionnement
        $approvisionnement = Approvisionnement::findOrFail($id);
        $approvisionnement->delete();

        return response()->json(['message' => 'Approvisionnement supprimé avec succès!']);
    }
}
