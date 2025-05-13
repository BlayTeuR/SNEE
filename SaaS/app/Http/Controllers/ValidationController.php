<?php

namespace App\Http\Controllers;

use App\Models\Depannage;
use App\Models\Entretien;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function index(Request $request)
    {
        $depannages = null;
        $entretiens = null;

        $type = $request->input('type', 'depannage');

        if($type == 'depannage') {
            $query = Depannage::with('historiques', 'types')
                ->where('statut', '=', 'Affecter')
                ->where('archived', '=', false);

            if($request->filled('nom')) {
                $query->where('nom', 'like', '%' . $request->input('nom') . '%');
            }

            if($request->input('jour_courant', 'on') === 'on') {
                $query->whereDate('date_depannage', '=', now()->format('Y-m-d'));
            }

            $depannages = $query->get();
        }
        else {
            $query = Entretien::with('historiques')
                ->where('archived', '=', false);

            if($request->filled('nom')) {
                $query->where('nom', 'like', '%' . $request->input('nom') . '%');
            }

            if($request->input('jour_courant', 'on') === 'on') {
                $query->whereDate('derniere_date', '=', now()->format('Y-m-d'));
            }

            $entretiens = $query->get();
        }
        return view('admin.validation', compact('depannages', 'entretiens', 'type'))
            ->with('success', 'Données récupérées avec succès.');
    }
}
