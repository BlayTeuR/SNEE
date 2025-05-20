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
                'technicien_id' => 'required|exists:users,id',
            ]);

            $depannage = Depannage::findOrFail($depannageId);
            $technicienId = $request->technicien_id;
            $confirmReplace = $request->input('confirm_replace');

            $existing = $depannage->affectation;

            if ($existing) {
                if (!$confirmReplace) {
                    return response()->json([
                        'message' => 'Technicien déjà affecté',
                        'technicien_actuel' => optional($existing->user)->name ?? 'Inconnu',
                        'needs_confirmation' => true
                    ], 200);
                } else {
                    $existing->delete();
                }
            }

            $affectation = new Affectation([
                'user_id' => $technicienId,
            ]);

            $depannage->affectation()->save($affectation);

            return response()->json(['message' => 'Technicien affecté avec succès'], 200);

        } catch (\Throwable $e) {
            \Log::error('Erreur lors de l\'affectation du technicien : ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Une erreur est survenue'], 500);
        }
    }

}
