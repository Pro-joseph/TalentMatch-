<?php

use App\Http\Controllers\AgentConversationController;
use App\Http\Controllers\AnalyseController;
use App\Http\Controllers\CandidatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OffreController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('offres', OffreController::class);
    Route::resource('candidats', CandidatController::class);

    Route::get('offres/{offre}/analyses', [AnalyseController::class, 'index'])->name('analyses.index');
    Route::post('offres/{offre}/analyser', [AnalyseController::class, 'store'])->name('analyses.store');
    Route::post('offres/{offre}/soumettre-cv', [OffreController::class, 'submitCv'])->name('offres.submit-cv');
    Route::get('analyses/{analyse}', [AnalyseController::class, 'show'])->name('analyses.show');
    Route::get('analyses/{analyse}/status', [AnalyseController::class, 'status'])->name('analyses.status');
    Route::post('analyses/{analyse}/retry', [AnalyseController::class, 'retry'])->name('analyses.retry');
    Route::get('offres/{offre}/comparer', [OffreController::class, 'comparer'])->name('offres.comparer');
    Route::post('offres/{offre}/comparer/verdict', [OffreController::class, 'comparerVerdict'])->name('offres.comparer.verdict');

    Route::get('assistant', [AgentConversationController::class, 'index'])->name('agent-conversations.index');
    Route::get('assistant/creer', [AgentConversationController::class, 'create'])->name('agent-conversations.create');
    Route::post('assistant', [AgentConversationController::class, 'store'])->name('agent-conversations.store');
    Route::get('assistant/{id}', [AgentConversationController::class, 'show'])->name('agent-conversations.show');
    Route::match(['GET', 'POST'], 'assistant/{id}/message', [AgentConversationController::class, 'message'])->name('agent-conversations.message');
    Route::delete('assistant/{id}', [AgentConversationController::class, 'destroy'])->name('agent-conversations.destroy');
});
