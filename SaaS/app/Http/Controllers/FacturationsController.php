<?php

namespace App\Http\Controllers;

use App\Models\Facturations;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FacturationsController extends Controller
{
    public function index(Request $request)
    {
        $query = Facturations::with('depannage');

        if ($request->filled('statut') && $request->statut !== 'all') {
            $query->where('statut', $request->statut);
        }

        // Filtrer par date d'émission
        if ($request->filled('emission')) {
            $query->whereDate('created_at', $request->emission);
        }

        // Filtrer par date d'intervention
        if ($request->filled('intervention')) {
            $query->whereDate('date_intervention', $request->intervention);
        }

        // Filtrer par nom (via la relation depannage)
        if ($request->filled('nom')) {
            $query->join('depannages', 'approvisionnements.depannage_id', '=', 'depannages.id')
                ->where('depannages.nom', 'like', '%' . $request->nom . '%');
        }

        // Filtrer par montant minimum et/ou maximum
        if ($request->filled('montant_min')) {
            $query->where('montant', '>=', $request->montant_min);
        }

        if ($request->filled('montant_max')) {
            $query->where('montant', '<=', $request->montant_max);
        }

        $facturations = $query->get();

        return view('admin.facturation', compact('facturations'));
    }

    public function destroy($id)
    {
        // Suppression de la facturation
        $facturation = Facturations::findOrFail($id);
        $facturation->delete();

        return response()->json(['message' => 'Facturation supprimée avec succès!']);
    }

    public function updateDate(Request $request, $id)
    {
        $request->validate([
            'date_intervention' => 'required|date',
        ]);

        // Récupérer la facturation
        $facturation = Facturations::findOrFail($id);
        $facturation->date_intervention = Carbon::parse($request->input('date_intervention'))->format('Y-m-d');

        $facturation->save();

        return redirect('/facturation')->with('success', 'date mise à jour avec succèes!');
    }

    public function updateMontant(Request $request, $id)
    {
        // Validation des données envoyées
        $request->validate([
            'montant' => 'required|numeric|min:0',
        ]);

        // Récupérer la facturation
        $facturation = Facturations::findOrFail($id);

        // Mettre à jour le montant
        $nouveauMontant = $request->input('montant');
        $facturation->montant = $nouveauMontant;
        $facturation->save();

        return redirect('/facturation')->with('success', 'montant mis à jour avec succès!');
    }
    public function updateStatus(Request $request, $id)
    {
        // Validation des données envoyées
        $request->validate([
            'statut' => 'required|string|in:Non envoyée,Envoyée',
        ]);

        // Récupérer la facturation
        $facturation = Facturations::findOrFail($id);

        // Mettre à jour le statut
        $nouveauStatut = $request->input('statut');
        $facturation->statut = $nouveauStatut;
        $facturation->save();

        return response()->json(['message' => 'Statut mis à jour avec succès!']);
    }
}
