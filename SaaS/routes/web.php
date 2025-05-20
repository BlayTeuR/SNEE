<?php

use App\Http\Controllers\ApprovisionnementController;
use App\Http\Controllers\DepanageController;
use App\Http\Controllers\EntretienController;
use App\Http\Controllers\FacturationsController;
use App\Http\Controllers\FicheController;
use App\Http\Controllers\HistoriqueController;
use App\Http\Controllers\PieceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\TechnicienDashboardController;
use App\Http\Controllers\TechnicienEntretienController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\ValidationController;
use App\Models\Fiche;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    $user = Auth::user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->isTechnicien()) {
        return redirect()->route('technicien.dashboard');
    }
    return redirect()->route('profile.edit');
});

Route::get('/confirmation', function () {
    return view('confirmation');
})->name('confirmation.page');

Route::get('/caconfirmation', function () {
    return view('caconfirmation');
})->name('caconfirmation.page');


Route::get('/form', function() {
    return view('form');
})->name('form');

Route::get('/caform', function() {
    return view('caform');
})->name('caform');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/carte', function () {
        return view('carte');
    })->name('carte');
});

Route::middleware(['auth', 'is_technicien'])->prefix('technicien')->as('technicien.')->group(function () {
    Route::get('/dashboard', [TechnicienDashboardController::class, 'index'])->name('dashboard');
    Route::get('/entretien', [TechnicienEntretienController::class, 'index'])->name('entretien');
    Route::get('/carte', [TechnicienDashboardController::class, 'index'])->name('carte');

    //Fiche
    Route::post('/fiche/{id}/del', [FicheController::class, 'delete'])->name('fiche.del');

    Route::get('/depannage/{id}', [TechnicienDashboardController::class, 'show'])->name('depannage.show');
    Route::get('/{id}/fiches/count/{type}', function ($id, $type) {
        $modelClass = match ($type) {
            'entretien' => \App\Models\Entretien::class,
            'depannage' => \App\Models\Depannage::class,
            default => null,
        };

        if (!$modelClass) {
            return response()->json(['error' => 'Type invalide'], 400);
        }

        $count = Fiche::where('user_id', $id)
            ->where('ficheable_type', $modelClass)
            ->count();

        return response()->json(['count' => $count]);
    });

});

// Admin
Route::middleware(['auth', 'is_admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/', [DepanageController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DepanageController::class, 'index'])->name('dashboard');
    Route::get('/historique', [HistoriqueController::class, 'index'])->name('historique');
    Route::get('/stat', [StatistiqueController::class, 'index'])->name('stat');
    Route::get('/facturation', [FacturationsController::class, 'index'])->name('facturation');
    Route::get('/entretien', [EntretienController::class, 'index'])->name('entretien');
    Route::get('/approvisionnement', [ApprovisionnementController::class, 'index'])->name('approvisionnement');
    Route::get('/validation', [ValidationController::class, 'index'])->name('validation');

    //Form
    Route::get('/adminform', function(){return view('admin.adminform');})->name('adminform');
    Route::get('/entretienform', function (){return view('admin.entretienform');})->name('entretienform');

    //Depannage
    Route::get('/depannage', [DepanageController::class, 'index'])->name('depannage');
    Route::get('/depannage/{id}', [DepanageController::class, 'show'])->name('depannage.show');
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
    Route::post('facturation/archiver/{id}', [FacturationsController::class, 'archiver'])->name('facturation.archiver');
    Route::post('facturation/desarchiver/{id}', [FacturationsController::class, 'desarchiver'])->name('facturation.desarchiver');

    //Entretien
    Route::get('/entretien', [EntretienController::class, 'index'])->name('entretien');
    Route::post('entretien/store', [EntretienController::class, 'store'])->name('entretien.store');
    Route::get('/entretien/{id}', [EntretienController::class, 'show'])->name('entretien.show')->middleware(['auth', 'verified']);
    Route::post('entretien/del/{id}', [EntretienController::class, 'destroy'])->name('entretien.del');
    Route::post('/entretien/{id}/update-date', [EntretienController::class, 'updateDate'])->name('entretien.update.date');
    Route::post('/entretien/{id}/archiver', [EntretienController::class, 'archiver'])->name('entretien.archiver');
    Route::post('/entretien/{id}/desarchiver', [EntretienController::class, 'desarchiver'])->name('entretien.desarchiver');

    //Fiche
    Route::post('/depannage/{depannage}/fiches', [FicheController::class, 'storeForDepannage'])->name('show.store');
    Route::post('entretien/{entretien}/fiches', [FicheController::class, 'storeForEntretien'])->name('entretien.show.store');
});

Route::get('admin/test-nav', function () {
    return view('admin.test-nav');
})->name('admin.testnav');

Route::get('/manifest.json', function () {
    $path = public_path('manifest.json');
    if (!File::exists($path)) {
        abort(404);
    }
    return response()->file($path, [
        'Content-Type' => 'application/json',
    ]);
});


 require __DIR__.'/auth.php';
