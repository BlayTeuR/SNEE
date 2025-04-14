<?php

use App\Http\Controllers\ApprovisionnementController;
use App\Http\Controllers\DepanageController;
use App\Http\Controllers\FacturationsController;
use App\Http\Controllers\PieceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/confirmation', function () {
    return view('confirmation');
})->name('confirmation.page');

// Passe les données de la table 'depannage' à la vue 'dashboard'
Route::get('/dashboard', [DepanageController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
// Passe les données de la table approvionnement à la vue 'approvisionnement'
Route::get('/approvisionnement', [ApprovisionnementController::class, 'index'])->middleware(['auth', 'verified'])->name('approvisionnement');
// Passe les données de la table facturation à la vue 'facturation'
Route::get('/facturation', [FacturationsController::class, 'index'])->middleware(['auth', 'verified'])->name('facturation');

Route::get('/form', function() {
    return view('form');
})->name('form');

Route::get('/entretien', function() {
    return view('entretien');
})->name('entretien');

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

// Route transfert spécial de données depuis BD

// Depannage
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/depannage', [DepanageController::class, 'index'])->name('depannage');
    Route::get('/depannage/{id}', [DepanageController::class, 'show'])->name('depannage.show')->middleware(['auth', 'verified']);
    Route::patch('/depannage/{id}/update-status', [DepanageController::class, 'updateStatus'])->name('depannage.updateStatus');
    Route::post('/depannage/store', [DepanageController::class, 'store'])->name('depannage.store');
    Route::post('depannage/del/{id}', [DepanageController::class, 'destroy'])->name('depannage.del');

    // Approvisionnement
    Route::patch('/approvisionnement/{id}/update-status', [ApprovisionnementController::class, 'updateStatus'])->name('approvisionnement.updateStatus');
    Route::post('approvisionnement/del/{id}', [ApprovisionnementController::class, 'destroy'])->name('approvisionnement.del');
    Route::post('/approvisionnement/store', [ApprovisionnementController::class, 'store'])->name('approvisionnement.store');

    //Piece
    Route::post('/approvisionnement/{id}/add-pieces', [PieceController::class, 'addPieces'])->name('pieces.add');
    Route::post('pieces/del/{id}', [PieceController::class, 'destroy'])->name('pieces.del');
    Route::put('/pieces/{id}/update', [PieceController::class, 'updatePiece'])->name('pieces.update');

    //Type
    Route::put('/type/{id}/update', [TypeController::class, 'updateType'])->name('update.type');

    //Facturation
    Route::post('facturation/del/{id}', [FacturationsController::class, 'destroy'])->name('facturation.del');
    Route::put('facturation/{id}/update-date', [FacturationsController::class, 'updateDate'])->name('facturation.update.date');
    Route::put('facturation/{id}/update-montant', [FacturationsController::class, 'updateMontant'])->name('facturation.update.montant');
    Route::patch('facturation/{id}/update-status', [FacturationsController::class, 'updateStatus'])->name('facturation.update.status');
});

 require __DIR__.'/auth.php';
