<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyseCvJob;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnalyseController extends Controller
{
    public function index(Offre $offre): View
    {
        $analyses = $offre->analyses()
            ->with('candidat')
            ->latest()
            ->paginate(10);

        return view('analyses.index', compact('offre', 'analyses'));
    }

    public function show(Analyse $analyse): View
    {
        $analyse->load(['offre', 'candidat']);

        return view('analyses.show', compact('analyse'));
    }

    public function store(Offre $offre): RedirectResponse
    {
        $candidat = Candidat::findOrFail(request('candidat_id'));

        $existing = Analyse::where('offre_id', $offre->id)
            ->where('candidat_id', $candidat->id)
            ->whereIn('status', ['pending', 'done'])
            ->first();

        if ($existing && $existing->status === 'pending') {
            return back()->with('info', 'Une analyse est déjà en cours pour ce candidat.');
        }

        AnalyseCvJob::dispatch($offre, $candidat);

        return to_route('analyses.index', $offre)
            ->with('success', 'Analyse lancée. Rafraîchissez la page dans quelques instants.');
    }
}
