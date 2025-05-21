<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Affectation;
use App\Models\Depannage;
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

}
