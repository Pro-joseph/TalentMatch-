<?php

namespace App\Ai\Tools;

use App\Models\Analyse;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetCandidateAnalysis implements Tool
{
    public function description(): Stringable|string
    {
        return 'Récupère le résultat d\'une analyse CV pour un candidat et une offre.';
    }

    public function handle(Request $request): Stringable|string
    {
        $analyse = Analyse::with(['offre', 'candidat'])
            ->findOrFail($request->input('analyse_id'));

        return json_encode([
            'candidat' => $analyse->candidat->nom,
            'offre' => $analyse->offre->titre,
            'matching_score' => $analyse->matching_score,
            'competences_extraites' => $analyse->competences_extraites,
            'annees_experience' => $analyse->annees_experience,
            'niveau_etudes' => $analyse->niveau_etudes,
            'langues' => $analyse->langues,
            'points_forts' => $analyse->points_forts,
            'lacunes' => $analyse->lacunes,
            'competences_manquantes' => $analyse->competences_manquantes,
            'recommandation' => $analyse->recommandation?->value,
            'justification' => $analyse->justification,
            'status' => $analyse->status,
        ], JSON_UNESCAPED_UNICODE);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'analyse_id' => $schema->integer()->required()->description('Identifiant de l\'analyse'),
        ];
    }
}
