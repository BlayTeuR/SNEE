<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Depannage;

class DeppanageController extends Controller
{
    public function index()
    {
        $depannages = Depannage::with('historiques')->get();

        return view('dashboard', compact('depannages'));
    }

    public function show($id)
    {
        $depannage = Depannage::findOrFail($id);
        return view('depannage.show', compact('depannage'));
    }
}
