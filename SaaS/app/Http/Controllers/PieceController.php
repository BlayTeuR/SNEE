<?php

namespace App\Http\Controllers;

use App\Models\Piece;
use Illuminate\Http\Request;

class PieceController extends Controller
{
    public function addPieces(Request $request, $approvisionnementId)
    {
        // Validation des données
        $request->validate([
            'libelle' => 'required|string|max:255',
            'quantite' => 'required|integer|min:1',
        ]);

        // Enregistrement de la pièce
        $piece = new Piece();
        $piece->approvisionnement_id = $approvisionnementId;
        $piece->libelle = $request->input('libelle');
        $piece->quantite = $request->input('quantite');
        $piece->save();

        return response()->json(['message' => 'Pièce ajoutée avec succès!']);
    }

    public function updatePiece(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'libelle' => 'required|string|max:255',
            'quantite' => 'required|integer|min:1',
        ]);

        // Récupérer la pièce
        $piece = new Piece();
        $piece->approvisionnement_id = $id;
        $piece->libelle = $request->input('libelle');
        $piece->quantite = $request->input('quantite');
        $piece->save();

        return redirect('/approvisionnement')->with('success', 'Pièce mise à jour avec succès!');
    }

    public function destroy($id)
    {
        // Suppression de la pièce
        $piece = Piece::findOrFail($id);
        $piece->delete();

        return response()->json(['message' => 'Pièce supprimée avec succès!']);
    }
}
