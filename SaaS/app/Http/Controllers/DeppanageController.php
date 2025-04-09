<?php

namespace App\Http\Controllers;

use App\Models\Depannage;
use Illuminate\Http\Request;

class DeppanageController extends Controller
{
    public function index()
    {
        $depannages = Depannage::with('historiques')->get();

        return view('dashboard', compact('depannages'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Validation des données envoyées
        $request->validate([
            'statut' => 'required|string|in:À planifier,Affecter,Approvisionnement,À facturer',
        ]);

        // Récupérer le depannage
        $depannage = Depannage::findOrFail($id);

        // Mettre à jour le statut
        $depannage->statut = $request->input('statut');
        $depannage->save();

        return response()->json(['message' => 'Statut mis à jour avec succès!']);
    }


    public function show($id)
    {
        $depannage = Depannage::findOrFail($id);
        return view('depannage.show', compact('depannage'));
    }
}
