<?php

namespace App\Http\Controllers;

use App\Models\Approvisionnement;
use Illuminate\Http\Request;

class ApprovisionnementController extends Controller
{
    public function index(){
        $approvisionnements = Approvisionnement::with('depannage')->get();

        return view('approvisionnement', compact('approvisionnements'));
    }
}
