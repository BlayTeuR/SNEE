<?php

namespace App\Http\Controllers;

use App\Models\Facturations;
use Illuminate\Http\Request;

class FacturationsController extends Controller
{
    public function index(Request $request)
    {
        $facturations = Facturations::with('depannage')->get();

        return view('facturation', compact('facturations'));
    }
}
