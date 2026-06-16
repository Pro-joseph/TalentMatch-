<?php

use App\Http\Controllers\AgentConversationController;
use App\Http\Controllers\AnalyseController;
use App\Http\Controllers\CandidatController;
use App\Http\Controllers\OffreController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
    Route::resource('offres', OffreController::class);
    Route::resource('candidats', CandidatController::class);

    Route::get('offres/{offre}/analyses', [AnalyseController::class, 'index'])->name('analyses.index');
    Route::post('offres/{offre}/analyser', [AnalyseController::class, 'store'])->name('analyses.store');
    Route::get('analyses/{analyse}', [AnalyseController::class, 'show'])->name('analyses.show');

    Route::get('assistant', [AgentConversationController::class, 'index'])->name('agent-conversations.index');
    Route::get('assistant/creer', [AgentConversationController::class, 'create'])->name('agent-conversations.create');
    Route::post('assistant', [AgentConversationController::class, 'store'])->name('agent-conversations.store');
    Route::get('assistant/{id}', [AgentConversationController::class, 'show'])->name('agent-conversations.show');
    Route::post('assistant/{id}/message', [AgentConversationController::class, 'message'])->name('agent-conversations.message');
    Route::delete('assistant/{id}', [AgentConversationController::class, 'destroy'])->name('agent-conversations.destroy');
});
