<?php

namespace App\Http\Controllers;

use App\Models\Depannage;
use App\Models\Fiche;
use Illuminate\Http\Request;

class FicheController extends Controller
{
    public function storeForDepannage(Request $request, $depannageId)
    {
        // Validation de la requête
        $request->validate([
            'techniciens' => 'required|array',
            'techniciens.*' => 'exists:users,id',
        ]);

        // Récupérer le dépannage
        $depannage = Depannage::findOrFail($depannageId);

        // Créer une fiche pour chaque technicien sélectionné
        foreach ($request->techniciens as $userId) {
            $depannage->fiches()->create([
                'user_id' => $userId,
            ]);
        }

        // Retourner une réponse après l'association des fiches
        return redirect()->back()->with('success', 'Fiches assignées avec succès au dépannage.');
    }
}
