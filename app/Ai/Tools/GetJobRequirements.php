<?php

namespace App\Ai\Tools;

use App\Models\Offre;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetJobRequirements implements Tool
{
    public function description(): Stringable|string
    {
        return 'Récupère les détails et exigences d\'une offre d\'emploi à partir de son identifiant.';
    }

    public function handle(Request $request): Stringable|string
    {
        $offre = Offre::findOrFail($request->integer('offre_id'));

        return json_encode([
            'titre' => $offre->titre,
            'description' => $offre->description,
            'competences_requises' => $offre->competences_requises,
            'experience_min' => $offre->experience_min,
        ], JSON_UNESCAPED_UNICODE);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'offre_id' => $schema->integer()->required()->description('Identifiant de l\'offre d\'emploi'),
        ];
    }
}
