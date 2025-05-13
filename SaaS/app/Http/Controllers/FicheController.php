<?php

namespace App\Http\Controllers;

use App\Models\Depannage;
use App\Models\Fiche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FicheController extends Controller
{

    public function storeForDepannage(Request $request, $depannageId)
    {
        $request->validate([
            'techniciens' => 'required|array',  // Assurez-vous que le tableau de techniciens est envoyé
            'techniciens.*' => 'exists:users,id', // Valider que tous les IDs sont valides
        ]);

        $depannage = Depannage::findOrFail($depannageId);
        $techniciens = $request->techniciens;

        foreach ($techniciens as $userId) {
            $existingFiche = Fiche::where('ficheable_id', $depannage->id)
                ->where('ficheable_type', Depannage::class)
                ->where('user_id', $userId)
                ->first();
            if(!$existingFiche){
                $fiche = new Fiche([
                    'user_id' => $userId,
                    'ficheable_type' => Depannage::class,
                    'ficheable_id' => $depannage->id,
                ]);

                $depannage->fiches()->save($fiche);
            }

        }

        return redirect()->back()->with('message', 'Fiches assignées avec succès');
    }
        public function delete($id)
        {
            try {
                $fiche = Fiche::findOrFail($id);
                $fiche->delete();

                return response()->json(['message' => 'Fiche supprimée avec succès.']);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Erreur lors de la suppression de la fiche: ' . $e->getMessage()], 500);
            }
        }
}
