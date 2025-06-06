<?php

namespace App\Http\Controllers;

use App\Models\Approvisionnement;
use Illuminate\Http\Request;

class ApprovisionnementController extends Controller
{
    public function index(Request $request) {
        $query = Approvisionnement::with('depannage', 'pieces');

        // Filtrer par statut
        if ($request->filled('statut') && $request->statut !== 'all') {
            $query->where('statut', $request->statut);
        }

        // Filtrer par date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filtrer par nom
        if ($request->filled('nom')) {
            $query->join('depannages', 'approvisionnements.depannage_id', '=', 'depannages.id')
                ->where('depannages.nom', 'like', '%' . $request->nom . '%');
        }

        // Filtrer par ID
        if ($request->filled('id')) {
            $query->where('depannage_id', $request->id);
        }

        // Récupérer les approvisionnements filtrés
        $approvisionnements = $query->where('archived', false)->paginate(15);

        // Retourner la vue avec les approvisionnements filtrés
        return view('admin.approvisionnement', compact('approvisionnements'));
    }

    public function updateStatus(Request $request, $id){

        $request->validate([
            'statut' => 'required|string|in:À planifier,En attente,Fait',
        ]);

        $approvisionnement = Approvisionnement::findOrFail($id);

        // Mettre à jour le statut
        $nouveauStatut = $request->input('statut');
        $archived = $request->input('archive');
        if($nouveauStatut === 'Fait') {
            $approvisionnement->date_validation = now();
            if(filter_var($archived, FILTER_VALIDATE_BOOLEAN)) {
                $approvisionnement->archived = true;
            }
        } else {
            $approvisionnement->archived = false;
            $approvisionnement->date_validation = null;
        }
        $approvisionnement->statut = $nouveauStatut;
        $approvisionnement->save();

        return response()->json(['message' => 'Statut mis à jour avec succès!']);
    }

    public function desarchiver(Request $request){

        $request->validate([
            'id' => 'required|integer|exists:approvisionnements,id',
        ]);

        $approvisionnement = Approvisionnement::findOrFail($request->id);
        $approvisionnement->archived = false;
        $approvisionnement->save();

        return response()->json(['message' => 'Approvisionnement désarchivé avec succès!']);
    }

    public function archiver(Request $request){
        $request->validate([
            'id' => 'required|integer|exists:approvisionnements,id',
        ]);

        $approvisionnement = Approvisionnement::findOrFail($request->id);
        $approvisionnement->archived = true;
        $approvisionnement->save();

        return response()->json(['message' => 'Approvisionnement archivé avec succès!']);
    }

    public function destroy($id)
    {
        $approvisionnement = Approvisionnement::findOrFail($id);
        $approvisionnement->delete();

        return response()->json(['message' => 'Approvisionnement supprimé avec succès!']);
    }
}
