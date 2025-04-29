<?php

use App\Http\Controllers\ApprovisionnementController;
use App\Http\Controllers\DepanageController;
use App\Http\Controllers\EntretienController;
use App\Http\Controllers\FacturationsController;
use App\Http\Controllers\HistoriqueController;
use App\Http\Controllers\PieceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/confirmation', function () {
    return view('confirmation');
})->name('confirmation.page');

Route::get('/caconfirmation', function () {
    return view('caconfirmation');
})->name('caconfirmation.page');

// Passe les données de la table 'depannage' à la vue 'dashboard'
Route::get('/dashboard', [DepanageController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
// Passe les données de la table approvionnement à la vue 'approvisionnement'
Route::get('/approvisionnement', [ApprovisionnementController::class, 'index'])->middleware(['auth', 'verified'])->name('approvisionnement');
// Passe les données de la table facturation à la vue 'facturation'
Route::get('/facturation', [FacturationsController::class, 'index'])->middleware(['auth', 'verified'])->name('facturation');

Route::get('/form', function() {
    return view('form');
})->name('form');

Route::get('/caform', function() {
    return view('caform');
})->name('caform');

Route::get('/carte', function () {
    return view('carte');
})->name('carte');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route transfert spécial de données depuis BD

// Depannage
Route::middleware(['auth', 'verified'])->group(function () {
    //Accès au vue
    Route::get('/historique', [HistoriqueController::class, 'index'])->name('historique');

    Route::get('/stat', [StatistiqueController::class, 'index'])->name('stat');

    Route::get('/entretien', function() {
        return view('entretien');
    })->name('entretien');

    //Form
    Route::get('/adminform', function(){return view('adminform');})->name('adminform');
    Route::get('/entretienform', function (){return view('entretienform');})->name('entretienform');

    //Depannage
    Route::get('/depannage', [DepanageController::class, 'index'])->name('depannage');
    Route::get('/depannage/{id}', [DepanageController::class, 'show'])->name('depannage.show')->middleware(['auth', 'verified']);
    Route::patch('/depannage/{id}/update-status', [DepanageController::class, 'updateStatus'])->name('depannage.updateStatus');
    Route::post('/depannage/store', [DepanageController::class, 'store'])->name('depannage.store');
    Route::post('depannage/del/{id}', [DepanageController::class, 'destroy'])->name('depannage.del');
    Route::post('/depannage/{id}/update-date', [DepanageController::class, 'updateDate'])->name('depannage.update.date');
    Route::post('depannage/{id}/archiver', [DepanageController::class, 'archiver'])->name('depannage.archiver');
    Route::post('/depannage/{id}/desarchiver', [DepanageController::class, 'desarchiver'])->name('depannage.desarchiver');

    // Approvisionnement
    Route::patch('/approvisionnement/{id}/update-status', [ApprovisionnementController::class, 'updateStatus'])->name('approvisionnement.updateStatus');
    Route::post('approvisionnement/del/{id}', [ApprovisionnementController::class, 'destroy'])->name('approvisionnement.del');
    Route::post('/approvisionnement/store', [ApprovisionnementController::class, 'store'])->name('approvisionnement.store');
    Route::post('/approvisionnement/{id}/desarchiver', [ApprovisionnementController::class, 'desarchiver'])->name('approvisionnement.desarchiver');
    Route::post('approvisionnement/{id}/archiver', [ApprovisionnementController::class, 'archiver'])->name('approvisionnement.archiver');
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

    //Entretien
    Route::get('/entretien', [EntretienController::class, 'index'])->name('entretien');
    Route::post('entretien/store', [EntretienController::class, 'store'])->name('entretien.store');
    Route::get('/entretien/{id}', [DepanageController::class, 'show'])->name('entretien.show')->middleware(['auth', 'verified']);
    Route::post('entretien/del/{id}', [EntretienController::class, 'destroy'])->name('entretien.del');
    Route::post('/entretien/{id}/update-date', [EntretienController::class, 'updateDate'])->name('entretien.update.date');
    Route::post('/entretien/{id}/archiver', [EntretienController::class, 'archiver'])->name('entretien.archiver');
});

 require __DIR__.'/auth.php';
