<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\InstructeurVoertuigController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [InstructeurVoertuigController::class, 'index'])->name('dashboard');

    Route::get('instructeurs/{instructeur}/voertuigen', [InstructeurVoertuigController::class, 'show'])->name('instructeurs.voertuigen');
    Route::get('instructeurs/{instructeur}/voertuigen/beschikbaar', [InstructeurVoertuigController::class, 'available'])->name('instructeurs.beschikbare-voertuigen');
    Route::post('instructeurs/{instructeur}/voertuigen/{voertuig}/toewijzen', [InstructeurVoertuigController::class, 'assign'])->name('instructeurs.voertuigen.assign');

    Route::get('voertuigen/{voertuig}/wijzigen', [InstructeurVoertuigController::class, 'edit'])->name('voertuigen.edit');
    Route::put('voertuigen/{voertuig}', [InstructeurVoertuigController::class, 'update'])->name('voertuigen.update');
    Route::get('voertuigen', [InstructeurVoertuigController::class, 'all'])->name('voertuigen.index');
    Route::delete('voertuigen/{voertuig}', [InstructeurVoertuigController::class, 'destroy'])->name('voertuigen.destroy');

    Route::delete('instructeurs/{instructeur}/voertuigen/{voertuig}', [InstructeurVoertuigController::class, 'removeAssignment'])->name('instructeurs.voertuigen.destroy');
});

require __DIR__.'/settings.php';
