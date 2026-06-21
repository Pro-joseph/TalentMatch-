<?php

namespace App\Ai\Tools;

use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class CompareCandidates implements Tool
{
    public function description(): Stringable|string
    {
        return 'Compare deux candidats pour une même offre. Retourne les scores et détails des deux analyses côte à côte. Utilise le titre de l\'offre et les noms des candidats.';
    }

    public function handle(Request $request): Stringable|string
    {
        $offre = Offre::where('titre', 'LIKE', '%'.$request['titre_offre'].'%')->first();

        if (! $offre) {
            return json_encode(['error' => 'Aucune offre trouvée avec ce titre.']);
        }

        $candidat1 = Candidat::where('nom', 'LIKE', '%'.$request['nom_candidat_1'].'%')->first();
        $candidat2 = Candidat::where('nom', 'LIKE', '%'.$request['nom_candidat_2'].'%')->first();

        if (! $candidat1 || ! $candidat2) {
            return json_encode(['error' => 'Un ou les deux candidats sont introuvables.']);
        }

        $analyses = Analyse::where('offre_id', $offre->id)
            ->whereIn('candidat_id', [$candidat1->id, $candidat2->id])
            ->get()
            ->keyBy('candidat_id');

        return json_encode([
            'offre' => $offre->titre,
            'candidat_1' => [
                'nom' => $candidat1->nom,
                'analyse' => $analyses->get($candidat1->id),
            ],
            'candidat_2' => [
                'nom' => $candidat2->nom,
                'analyse' => $analyses->get($candidat2->id),
            ],
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'titre_offre' => $schema->string()->required(),
            'nom_candidat_1' => $schema->string()->required(),
            'nom_candidat_2' => $schema->string()->required(),
        ];
    }
}
