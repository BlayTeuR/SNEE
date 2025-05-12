<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fiche;
use Illuminate\Http\Request;

class TechnicienDashboardController extends Controller
{
    public function index(Request $request)
    {
        $technicienId = auth()->user()->id;

        $fiches = Fiche::where('user_id', '=', $technicienId)->get();

        return view('technicien.dashboard', compact('fiches'));
    }

    public function show(Request $request){
        //
    }
}
