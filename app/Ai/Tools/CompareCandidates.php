<?php

namespace App\Ai\Tools;

use App\Jobs\AnalyseCvJob;
use App\Models\Analyse;
use App\Models\Offre;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class CompareCandidates implements Tool
{
    public function description(): Stringable|string
    {
        return 'Compare plusieurs candidats pour une offre d\'emploi donnée. '.
            'Si un candidat n\'a pas encore été analysé, l\'analyse est automatiquement lancée.';
    }

    public function handle(Request $request): Stringable|string
    {
        $offre = Offre::with('analyses.candidat')->findOrFail($request->integer('offre_id'));
        $candidateIds = $request->array('candidat_ids');

        $missing = [];
        $results = [];

        foreach ($candidateIds as $candidatId) {
            $analyse = $offre->analyses()
                ->where('candidat_id', $candidatId)
                ->first();

            if (! $analyse) {
                $missing[] = $candidatId;

                continue;
            }

            if ($analyse->status === 'failed') {
                $results[] = [
                    'candidat' => $analyse->candidat->nom,
                    'error' => 'Analyse échouée. Veuillez soumettre à nouveau le CV.',
                ];

                continue;
            }

            if ($analyse->status === 'pending') {
                $results[] = [
                    'candidat' => $analyse->candidat->nom,
                    'message' => 'Analyse en cours...',
                ];

                continue;
            }

            $results[] = [
                'candidat' => $analyse->candidat->nom,
                'matching_score' => $analyse->matching_score,
                'annees_experience' => $analyse->annees_experience,
                'niveau_etudes' => $analyse->niveau_etudes,
                'points_forts' => $analyse->points_forts,
                'competences_manquantes' => $analyse->competences_manquantes,
                'recommandation' => $analyse->recommandation?->value,
            ];
        }

        if (! empty($missing)) {
            $messages = [];
            foreach ($missing as $candidatId) {
                $candidat = $offre->analyses->firstWhere('candidat_id', $candidatId)?->candidat;

                Analyse::create([
                    'offre_id' => $offre->id,
                    'candidat_id' => $candidatId,
                    'status' => 'pending',
                ]);

                AnalyseCvJob::dispatch($offre, $candidat ?? $candidatId);

                $nom = $candidat?->nom ?? "Candidat #{$candidatId}";
                $messages[] = "Analyse lancée automatiquement pour {$nom}.";
            }

            $results[] = ['message' => implode(' ', $messages)];
        }

        return json_encode([
            'offre' => $offre->titre,
            'candidats' => $results,
        ], JSON_UNESCAPED_UNICODE);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'offre_id' => $schema->integer()->required()->description('Identifiant de l\'offre'),
            'candidat_ids' => $schema->array(
                $schema->integer()
            )->required()->description('Identifiants des candidats à comparer'),
        ];
    }
}
