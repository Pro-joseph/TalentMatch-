<?php

namespace App\Ai\Tools;

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
        return 'Compare plusieurs candidats pour une offre d\'emploi donnée à partir de leurs identifiants.';
    }

    public function handle(Request $request): Stringable|string
    {
        $offre = Offre::findOrFail($request->integer('offre_id'));
        $candidateIds = $request->array('candidat_ids');

        $analyses = Analyse::with('candidat')
            ->where('offre_id', $offre->id)
            ->whereIn('candidat_id', $candidateIds)
            ->where('status', 'done')
            ->get();

        $comparison = $analyses->map(fn ($a) => [
            'candidat' => $a->candidat->nom,
            'matching_score' => $a->matching_score,
            'annees_experience' => $a->annees_experience,
            'niveau_etudes' => $a->niveau_etudes,
            'points_forts' => $a->points_forts,
            'competences_manquantes' => $a->competences_manquantes,
            'recommandation' => $a->recommandation?->value,
        ]);

        return json_encode([
            'offre' => $offre->titre,
            'candidats' => $comparison,
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
