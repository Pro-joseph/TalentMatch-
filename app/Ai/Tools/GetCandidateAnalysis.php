<?php

namespace App\Ai\Tools;

use App\Jobs\AnalyseCvJob;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetCandidateAnalysis implements Tool
{
    public function description(): Stringable|string
    {
        return 'Récupère le résultat d\'une analyse CV pour un candidat et une offre. '.
            'Si l\'analyse n\'existe pas, elle est automatiquement lancée.';
    }

    public function handle(Request $request): Stringable|string
    {
        $analyseId = $request->integer('analyse_id');
        $offreId = $request->integer('offre_id', 0);
        $candidatId = $request->integer('candidat_id', 0);

        if ($analyseId) {
            $analyse = Analyse::with(['offre', 'candidat'])->find($analyseId);
        } elseif ($offreId && $candidatId) {
            $analyse = Analyse::with(['offre', 'candidat'])
                ->where('offre_id', $offreId)
                ->where('candidat_id', $candidatId)
                ->first();
        } else {
            return json_encode([
                'error' => 'Fournissez un analyse_id ou un couple offre_id + candidat_id.',
            ], JSON_UNESCAPED_UNICODE);
        }

        if (! $analyse) {
            if ($offreId && $candidatId) {
                $offre = Offre::find($offreId);
                $candidat = Candidat::find($candidatId);

                if (! $offre || ! $candidat) {
                    return json_encode([
                        'error' => 'Offre ou candidat introuvable.',
                    ], JSON_UNESCAPED_UNICODE);
                }

                $analyse = Analyse::create([
                    'offre_id' => $offre->id,
                    'candidat_id' => $candidat->id,
                    'status' => 'pending',
                ]);

                AnalyseCvJob::dispatch($offre, $candidat);

                return json_encode([
                    'message' => 'Analyse lancée automatiquement pour '.$candidat->nom.' sur l\'offre "'.$offre->titre.'". Revenez dans quelques instants pour voir les résultats.',
                    'status' => 'pending',
                    'analyse_id' => $analyse->id,
                ], JSON_UNESCAPED_UNICODE);
            }

            return json_encode([
                'error' => 'Analyse introuvable.',
            ], JSON_UNESCAPED_UNICODE);
        }

        if ($analyse->status === 'failed') {
            return json_encode([
                'error' => 'L\'analyse pour '.$analyse->candidat->nom.' a échoué. Veuillez soumettre à nouveau le CV.',
                'status' => 'failed',
            ], JSON_UNESCAPED_UNICODE);
        }

        if ($analyse->status === 'pending') {
            return json_encode([
                'message' => 'Analyse en cours pour '.$analyse->candidat->nom.'... Revenez dans quelques instants.',
                'status' => 'pending',
                'analyse_id' => $analyse->id,
            ], JSON_UNESCAPED_UNICODE);
        }

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
            'analyse_id' => $schema->integer()->nullable()->description('Identifiant de l\'analyse'),
            'offre_id' => $schema->integer()->nullable()->description('Identifiant de l\'offre (si pas d\'analyse_id)'),
            'candidat_id' => $schema->integer()->nullable()->description('Identifiant du candidat (si pas d\'analyse_id)'),
        ];
    }
}
