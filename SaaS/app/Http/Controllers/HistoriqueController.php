<?php

namespace App\Http\Controllers;

use App\Models\Approvisionnement;
use App\Models\Depannage;
use App\Models\Entretien;
use App\Models\Facturations;
use Illuminate\Http\Request;

class HistoriqueController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'depannage');
        $validatedTypes = ['depannage', 'approvisionnement', 'facturation', 'entretiens'];

        if (!in_array($type, $validatedTypes)) {
            return redirect()->route('admin.historique')->with('error', 'Type de données invalide.');
        }

        $model = null;
        switch ($type) {
            case 'depannage':
                $model = Depannage::with('historiques', 'types')->where('statut', '=', 'À facturer')->get()->where('archived', '=', true);
                break;
            case 'approvisionnement':
                $model = Approvisionnement::with('depannage', 'pieces')->where('statut', '=', 'Fait')->where('archived', '=', true)->get();
                break;
            case 'facturation':
                $model = Facturations::with('depannage')->where('Statut', '=', 'Envoyée')->where('archived', '=', true)->get();
                break;
                case 'entretiens':
                    $model = Entretien::with('historiques')->where('archived', '=', true)->get();
                    break;
        }

        return view('admin.historique', compact('type', 'model'))->with('success', 'Données récupérées avec succès.');
    }

}
