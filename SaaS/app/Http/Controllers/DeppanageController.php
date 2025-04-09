<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Depannage;

class DeppanageController extends Controller
{
    public function index()
    {
        $depannage = Depannage::all();

        return view('dashboard', compact('depannage'));
    }

    public function show($id)
    {
        $depannage = Depannage::findOrFail($id);
        return view('depannage.show', compact('depannage'));
    }
}
