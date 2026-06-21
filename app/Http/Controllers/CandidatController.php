<?php

namespace App\Http\Controllers;

use App\Models\Candidat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CandidatController extends Controller
{
    public function index(): View
    {
        $candidats = Candidat::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('candidats.index', compact('candidats'));
    }

    public function create(): View
    {
        return view('candidats.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'cv_texte' => ['required', 'string'],
        ]);

        $candidat = auth()->user()->candidats()->create($validated);

        return to_route('candidats.show', $candidat)
            ->with('success', 'Candidat ajouté avec succès.');
    }

    public function show(Candidat $candidat): View
    {
        $candidat->load('analyses.offre');

        return view('candidats.show', compact('candidat'));
    }

    public function edit(Candidat $candidat): View
    {
        return view('candidats.edit', compact('candidat'));
    }

    public function update(Request $request, Candidat $candidat): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'cv_texte' => ['required', 'string'],
        ]);

        $candidat->update($validated);

        return to_route('candidats.show', $candidat)
            ->with('success', 'Candidat mis à jour avec succès.');
    }

    public function destroy(Candidat $candidat): RedirectResponse
    {
        $candidat->delete();

        return to_route('candidats.index')
            ->with('success', 'Candidat supprimé avec succès.');
    }
}
