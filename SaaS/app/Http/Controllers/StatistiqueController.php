<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    public function index(){
        $depannages = \App\Models\Depannage::all();
        $approvisionnements = \App\Models\Approvisionnement::all();
        $facturations = \App\Models\Facturations::all();

        return view('stat', compact('depannages', 'approvisionnements', 'facturations'));
    }
}
