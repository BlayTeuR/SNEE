<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Entretien;
use App\Models\Fiche;

class TechnicienEntretienController extends Controller
{

    public function index()
    {
        // Récupérer les entretiens pour le technicien connecté
        $technicienId = auth()->user()->id;
        $fiches = Fiche::where('user_id', $technicienId)
            ->where('ficheable_type', Entretien::class)
            ->get();

        return view('technicien.entretien', compact('fiches'));
    }

    public function show($id)
    {

        $entretien = Entretien::findOrFail($id);
        return view('technicien.entretien.show', compact('entretien'));
    }
}
