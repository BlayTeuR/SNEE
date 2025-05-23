<?php

namespace App\Http\Controllers;

use App\Models\Depannage;
use App\Models\Entretien;
use Illuminate\Http\Request;

class CarteController extends Controller
{
    public function index()
    {
        $depannage = Depannage::with('historiques', 'types');
        $depannage = $depannage->where('archived', false)->get();

        $entretien = Entretien::with('historiques');
        $entretien = $entretien->where('archived', false)->get();

        if($user = auth()->user()) {
            if($user->isAdmin()) {
                return view('admin.carte', compact('depannage', 'entretien'));
            } elseif($user->isTechnicien()) {
                return view('technicien.carte', compact('depannage', 'entretien'));
            }
        }
        return true;
    }
}
