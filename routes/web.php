<?php

use App\Http\Controllers\OffreController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::resource('offres', OffreController::class);
});
