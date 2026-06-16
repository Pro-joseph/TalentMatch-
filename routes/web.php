<?php

use App\Http\Controllers\AnalyseController;
use App\Http\Controllers\CandidatController;
use App\Http\Controllers\OffreController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::resource('offres', OffreController::class);
    Route::resource('candidats', CandidatController::class);

    Route::get('offres/{offre}/analyses', [AnalyseController::class, 'index'])->name('analyses.index');
    Route::post('offres/{offre}/analyser', [AnalyseController::class, 'store'])->name('analyses.store');
    Route::get('analyses/{analyse}', [AnalyseController::class, 'show'])->name('analyses.show');
});
