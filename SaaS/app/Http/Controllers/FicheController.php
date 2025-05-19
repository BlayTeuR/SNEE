<?php

namespace App\Http\Controllers;

use App\Models\Depannage;
use App\Models\Entretien;
use App\Models\Fiche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FicheController extends Controller
{

    public function storeForDepannage(Request $request, $depannageId)
    {
        $request->validate([
            'techniciens' => 'required|array',
            'techniciens.*' => 'exists:users,id',
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
            } else {
                // retourner une réponse json indiquant que la fiche existe déjà pour un technicien donné
            }
        }
        return response()->json(['message' => 'Fiches assignées avec succès'], 200);
    }

    public function storeForEntretien(Request $request, $entretienId)
    {
        $request->validate([
            'techniciens' => 'required|array',
            'techniciens.*' => 'exists:users,id',
        ]);

        $entretien = Entretien::findOrFail($entretienId);
        $techniciens = $request->techniciens;

        foreach ($techniciens as $userId) {
            $existingFiche = Fiche::where('ficheable_id', $entretien->id)
                ->where('ficheable_type', Entretien::class)
                ->where('user_id', $userId)
                ->first();
            if(!$existingFiche){
                $fiche = new Fiche([
                    'user_id' => $userId,
                    'ficheable_type' => Entretien::class,
                    'ficheable_id' => $entretien->id,
                ]);

                $entretien->fiches()->save($fiche);
            } else {
                // retourner une réponse json indiquant que la fiche existe déjà pour un technicien donné
            }
        }
        return response()->json(['message' => 'Fiches assignées avec succès'], 200);
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
