<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOffreRequest;
use App\Http\Requests\UpdateOffreRequest;
use App\Models\Offre;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OffreController extends Controller
{
    public function index(): View
    {
        $offres = Offre::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('offres.index', compact('offres'));
    }

    public function create(): View
    {
        return view('offres.create');
    }

    public function store(StoreOffreRequest $request): RedirectResponse
    {
        $offre = auth()->user()->offres()->create($request->validated());

        return to_route('offres.show', $offre)
            ->with('success', 'Offre créée avec succès.');
    }

    public function show(Offre $offre): View
    {
        $this->authorize('view', $offre);

        return view('offres.show', compact('offre'));
    }

    public function edit(Offre $offre): View
    {
        $this->authorize('update', $offre);

        return view('offres.edit', compact('offre'));
    }

    public function update(UpdateOffreRequest $request, Offre $offre): RedirectResponse
    {
        $this->authorize('update', $offre);

        $offre->update($request->validated());

        return to_route('offres.show', $offre)
            ->with('success', 'Offre mise à jour avec succès.');
    }

    public function destroy(Offre $offre): RedirectResponse
    {
        $this->authorize('delete', $offre);

        $offre->delete();

        return to_route('offres.index')
            ->with('success', 'Offre supprimée avec succès.');
    }
}
