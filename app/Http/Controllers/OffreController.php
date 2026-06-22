<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ComparativeAnalyzer;
use App\Http\Requests\StoreOffreRequest;
use App\Http\Requests\UpdateOffreRequest;
use App\Jobs\AnalyseCvJob;
use App\Models\Analyse;
use App\Models\Offre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Ai\Enums\Lab;

class OffreController extends Controller
{
    public function index(): View
    {
        $offres = Offre::where('user_id', auth()->id())
            ->withCount('analyses')
            ->with(['analyses' => fn ($q) => $q->latest()->limit(1)])
            ->latest()
            ->paginate(10);

        $pendingIds = Analyse::whereIn('offre_id', $offres->pluck('id'))
            ->where('status', 'pending')
            ->pluck('id');

        $statusUrls = $pendingIds->mapWithKeys(fn ($id) => [$id => route('analyses.status', $id)]);

        return view('offres.index', compact('offres', 'pendingIds', 'statusUrls'));
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

        $analyses = $offre->analyses()
            ->with('candidat')
            ->orderByDesc('matching_score')
            ->get();

        $analysedCandidatIds = $analyses->pluck('candidat_id');

        $candidats = auth()->user()->candidats()
            ->whereNotIn('id', $analysedCandidatIds)
            ->latest()
            ->get();

        return view('offres.show', compact('offre', 'analyses', 'candidats'));
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

    public function submitCv(Request $request, Offre $offre): RedirectResponse
    {
        $this->authorize('view', $offre);

        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'cv_texte' => ['required', 'string'],
        ]);

        $candidat = auth()->user()->candidats()->create($data);

        $analyse = Analyse::create([
            'offre_id' => $offre->id,
            'candidat_id' => $candidat->id,
            'status' => 'pending',
        ]);

        AnalyseCvJob::dispatch($offre, $candidat);

        return to_route('offres.show', $offre)
            ->with('analyse_id', $analyse->id)
            ->with('success', 'CV soumis. Analyse en cours…');
    }

    public function comparer(Request $request, Offre $offre): View
    {
        $this->authorize('view', $offre);

        if ($request->has('candidats')) {
            $validated = $request->validate([
                'candidats' => ['required', 'array', 'min:2', 'max:15'],
                'candidats.*' => ['required', 'string', 'exists:analyses,candidat_id'],
            ]);

            $analyses = Analyse::where('offre_id', $offre->id)
                ->whereIn('candidat_id', $validated['candidats'])
                ->with('candidat')
                ->get();
        } else {
            $analyses = $offre->analyses()
                ->with('candidat')
                ->where('status', 'done')
                ->get();
        }

        $analyses = $analyses->sortByDesc('matching_score')->values();

        $topCount = min(4, $analyses->count());

        return view('offres.comparer', compact('offre', 'analyses', 'topCount'));
    }

    public function comparerVerdict(Request $request, Offre $offre): JsonResponse
    {
        $this->authorize('view', $offre);

        $validated = $request->validate([
            'analyse_ids' => ['required', 'array', 'min:2', 'max:15'],
            'analyse_ids.*' => ['required', 'integer', 'exists:analyses,id'],
        ]);

        $analyses = Analyse::with('candidat')
            ->whereIn('id', $validated['analyse_ids'])
            ->where('offre_id', $offre->id)
            ->get();

        if ($analyses->isEmpty()) {
            return response()->json(['error' => 'Aucune analyse trouvée.'], 404);
        }

        $pending = $analyses->where('status', 'pending');
        if ($pending->isNotEmpty()) {
            return response()->json([
                'error' => 'Certaines analyses sont encore en cours : '.
                    $pending->map(fn ($a) => $a->candidat?->nom ?? 'Candidat')->implode(', ').
                    '. Veuillez patienter.',
            ], 422);
        }

        $failed = $analyses->where('status', 'failed');
        if ($failed->isNotEmpty()) {
            return response()->json([
                'error' => 'L\'analyse a échoué pour : '.
                    $failed->map(fn ($a) => $a->candidat?->nom ?? 'Candidat')->implode(', ').
                    '. Veuillez soumettre à nouveau le CV.',
            ], 422);
        }

        $competences = is_array($offre->competences_requises)
            ? implode(', ', $offre->competences_requises)
            : $offre->competences_requises;

        $candidatsData = $analyses->map(fn ($a) => [
            'nom' => $a->candidat?->nom ?? 'Candidat',
            'score' => $a->matching_score,
            'experience' => $a->annees_experience,
            'etudes' => $a->niveau_etudes,
            'competences' => $a->competences_extraites,
            'points_forts' => $a->points_forts,
            'lacunes' => $a->lacunes,
            'competences_manquantes' => $a->competences_manquantes,
            'recommandation' => $a->recommandation?->value,
        ])->toArray();

        $prompt = <<<PROMPT
OFFRE D'EMPLOI :
Titre : {$offre->titre}
Description : {$offre->description}
Compétences requises : {$competences}
Expérience min : {$offre->experience_min} an(s)

CANDIDATS À COMPARER :
{$this->formatCandidatsForPrompt($candidatsData)}

Compare ces candidats et donne ton verdict.
PROMPT;

        try {
            $response = (new ComparativeAnalyzer)->prompt(
                $prompt,
                provider: Lab::Groq,
                model: 'openai/gpt-oss-120b',
            );

            return response()->json([
                'rankings' => $response['rankings'],
                'analyse' => $response['analyse'],
                'recommandation' => $response['recommandation'],
            ]);
        } catch (\Throwable $e) {
            $message = match (true) {
                str_contains($e->getMessage(), 'rate limit') || str_contains($e->getMessage(), 'Rate limit') => 'Limite de débit de l\'API IA atteinte. Veuillez patienter quelques instants puis réessayer.',
                str_contains($e->getMessage(), 'overloaded') || str_contains($e->getMessage(), 'Overloaded') => 'Le service d\'IA est temporairement surchargé. Veuillez réessayer dans quelques instants.',
                str_contains($e->getMessage(), 'insufficient credits') || str_contains($e->getMessage(), 'Insufficient') => 'Crédits IA insuffisants. Veuillez contacter l\'administrateur.',
                str_contains($e->getMessage(), 'does not support') => 'Le modèle d\'IA utilisé ne supporte pas cette fonctionnalité. Veuillez contacter l\'administrateur.',
                default => 'Le service d\'IA est temporairement indisponible. Veuillez réessayer plus tard.',
            };

            return response()->json(['error' => $message], 500);
        }
    }

    private function formatCandidatsForPrompt(array $candidats): string
    {
        $lines = [];
        foreach ($candidats as $i => $c) {
            $lines[] = 'Candidat #'.($i + 1).' : '.$c['nom'];
            $lines[] = '  Score : '.($c['score'] ?? 'N/A');
            $lines[] = '  Expérience : '.($c['experience'] ?? 'N/A').' ans';
            $lines[] = '  Études : '.($c['etudes'] ?? 'N/A');
            $lines[] = '  Compétences : '.implode(', ', $c['competences'] ?? []);
            $lines[] = '  Points forts : '.implode('; ', $c['points_forts'] ?? []);
            $lines[] = '  Lacunes : '.implode('; ', $c['lacunes'] ?? []);
            $lines[] = '  Compétences manquantes : '.implode(', ', $c['competences_manquantes'] ?? []);
            $lines[] = '  Recommandation initiale : '.($c['recommandation'] ?? 'N/A');
            $lines[] = '';
        }

        return implode("\n", $lines);
    }
}
