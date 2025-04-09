<?php

use App\Http\Controllers\DeppanageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DeppanageController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/facturation', function () {
    return view('facturation');
})->middleware(['auth', 'verified'])->name('facturation');

Route::get('/form', function() {
    return view('form');
});

Route::get('/stat', function () {
    return view('stat');
})->middleware(['auth', 'verified'])->name('stat');

Route::get('/carte', function () {
    return view('carte');
})->middleware(['auth', 'verified'])->name('carte');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route transfert de données depuis BD

// Depannage
 Route::get('/depannage/{id}', [\App\Http\Controllers\DeppanageController::class, 'show'])->name('depannage.show');
Route::patch('/depannage/{id}/update-status', [DeppanageController::class, 'updateStatus'])->name('depannage.updateStatus');
require __DIR__.'/auth.php';
