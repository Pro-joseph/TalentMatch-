<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyseCvJob;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyseController extends Controller
{
    public function index(Offre $offre): View
    {
        $this->authorize('view', $offre);

        $analyses = $offre->analyses()->with('candidat')->paginate(20);

        return view('analyses.index', compact('offre', 'analyses'));
    }

    public function show(Analyse $analyse): View
    {
        $this->authorize('view', $analyse->offre);

        return view('analyses.show', compact('analyse'));
    }

    public function store(Request $request, Offre $offre): RedirectResponse
    {
        $this->authorize('update', $offre);

        $data = $request->validate([
            'candidat_id' => ['required', 'exists:candidats,id'],
        ]);

        dispatch(new AnalyseCvJob($offre, Candidat::findOrFail($data['candidat_id'])));

        return to_route('analyses.index', $offre)
            ->with('success', 'Analyse lancée en arrière-plan.');
    }

    public function status(Analyse $analyse): JsonResponse
    {
        $this->authorize('view', $analyse->offre);

        return response()->json([
            'status' => $analyse->status,
        ]);
    }

    public function retry(Analyse $analyse): RedirectResponse
    {
        $this->authorize('update', $analyse->offre);

        if ($analyse->status !== 'failed') {
            return back()->with('error', 'Seules les analyses échouées peuvent être relancées.');
        }

        dispatch(new AnalyseCvJob($analyse->offre, $analyse->candidat));

        return to_route('analyses.index', $analyse->offre)
            ->with('success', 'Analyse relancée en arrière-plan.');
    }
}
