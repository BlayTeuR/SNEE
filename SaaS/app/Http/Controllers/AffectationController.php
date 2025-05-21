<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Affectation;
use App\Models\Depannage;
use App\Models\Entretien;
use Illuminate\Http\Request;

class AffectationController extends Controller
{

    public function storeForDepannage(Request $request, $depannageId)
    {
        try {
            $request->validate([
                'techniciens' => 'required|array|min:1',
                'techniciens.*' => 'exists:users,id',
            ]);

            $depannage = Depannage::findOrFail($depannageId);

            foreach ($request->techniciens as $technicienId) {
                $affectation = new Affectation([
                    'user_id' => $technicienId,
                    'affecteable_type' => Depannage::class,
                    'affecteable_id' => $depannage->id,
                ]);

                $depannage->affectations()->save($affectation);
            }

            return response()->json(['message' => 'Technicien(s) affecté(s) avec succès'], 200);

        } catch (\Throwable $e) {
            \Log::error('Erreur lors de l\'affectation des techniciens : ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Une erreur est survenue'], 500);
        }
    }

    public function storeForEntretien(Request $request, $entretienId){
        try {
            $request->validate([
                'techniciens' => 'required|array|min:1',
                'techniciens.*' => 'exists:users,id',
            ]);

            $entretien = Entretien::findOrFail($entretienId);

            foreach ($request->techniciens as $technicienId) {
                $affectation = new Affectation([
                    'user_id' => $technicienId,
                    'affecteable_type' => Entretien::class,
                    'affecteable_id' => $entretien->id,
                ]);

                $entretien->affectations()->save($affectation);
            }

            return response()->json(['message' => 'Technicien(s) affecté(s) avec succès'], 200);

        } catch (\Throwable $e) {
            \Log::error('Erreur lors de l\'affectation des techniciens : ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Une erreur est survenue'], 500);
        }
    }

    public function destroyForDepannage($depannage, $technicien)
    {

        \Log::info("Params reçus", ['depannage' => $depannage, 'technicien' => $technicien]);

        $affectation = Affectation::where('user_id', $technicien)
            ->where('affecteable_type', Depannage::class)
            ->where('affecteable_id', $depannage)
            ->first();

        if (!$affectation) {
            \Log::warning("Affectation introuvable");
            return response()->json(['message' => 'Affectation introuvable'], 404);
        }

        $affectation->delete();

        \Log::info("Affectation supprimée");

        return response()->json(['message' => 'Affectation supprimée avec succès'], 200);
    }

    public function destroyForEntretien($entretien, $technicien)
    {
        \Log::info("Params reçus", ['entretien' => $entretien, 'technicien' => $technicien]);

        $affectation = Affectation::where('user_id', $technicien)
            ->where('affecteable_type', Entretien::class)
            ->where('affecteable_id', $entretien)
            ->first();

        if (!$affectation) {
            \Log::warning("Affectation introuvable");
            return response()->json(['message' => 'Affectation introuvable'], 404);
        }

        $affectation->delete();

        \Log::info("Affectation supprimée");

        return response()->json(['message' => 'Affectation supprimée avec succès'], 200);
    }

}
