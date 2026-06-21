<?php

namespace App\Ai\Tools;

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
        return 'Récupère l\'analyse d\'un candidat pour une offre : score, points forts, lacunes, compétences manquantes, recommandation et justification. Utilise le nom du candidat et le titre de l\'offre.';
    }

    public function handle(Request $request): Stringable|string
    {
        $offre = Offre::where('titre', 'LIKE', '%'.$request['titre_offre'].'%')->first();
        $candidat = Candidat::where('nom', 'LIKE', '%'.$request['nom_candidat'].'%')->first();

        if (! $offre || ! $candidat) {
            return json_encode(['error' => 'Offre ou candidat introuvable.']);
        }

        $analyse = Analyse::where('candidat_id', $candidat->id)
            ->where('offre_id', $offre->id)
            ->first();

        if (! $analyse) {
            return json_encode(['error' => 'Aucune analyse trouvée pour ce candidat sur cette offre.']);
        }

        return json_encode([
            'matching_score' => $analyse->matching_score,
            'points_forts' => $analyse->points_forts,
            'lacunes' => $analyse->lacunes,
            'competences_manquantes' => $analyse->competences_manquantes,
            'recommandation' => $analyse->recommandation?->value,
            'justification' => $analyse->justification,
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'nom_candidat' => $schema->string()->required(),
            'titre_offre' => $schema->string()->required(),
        ];
    }
}
