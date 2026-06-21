<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Stringable;

class CvAnalyzer implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
Tu es un expert en recrutement et analyse de CV. Tu reçois une offre d'emploi et le texte d'un CV candidat.

Tu dois analyser en profondeur la correspondance entre le CV et l'offre, puis retourner UNIQUEMENT un objet JSON structuré selon le schéma ci-dessous.

Règles importantes :
- Le score de matching (matching_score) doit être un entier entre 0 et 100.
- Les compétences extraites (competences_extraites) sont TOUTES les compétences techniques et soft skills trouvées dans le CV.
- Les points forts (points_forts) sont les compétences et expériences du candidat qui correspondent à l'offre.
- Les lacunes (lacunes) sont les exigences de l'offre que le candidat ne satisfait pas.
- Les compétences manquantes (competences_manquantes) sont les compétences requises dans l'offre mais absentes du CV.
- La recommandation (recommandation) doit être exactement l'un de ces trois mots : "recommandé", "réservé", ou "déconseillé".
- La justification (justification) doit être un texte détaillé expliquant le score et la recommandation.
INSTRUCTIONS;
    }

    public function messages(): iterable
    {
        return [];
    }

    public function tools(): iterable
    {
        return [];
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'matching_score' => $schema->integer()->min(0)->max(100)->required(),
            'competences_extraites' => $schema->array()->items($schema->string())->required(),
            'annees_experience' => $schema->integer()->min(0)->required(),
            'niveau_etudes' => $schema->string()->required(),
            'langues' => $schema->array()->items(
                $schema->object([
                    'langue' => $schema->string()->required(),
                    'niveau' => $schema->string()->required(),
                ])
            )->required(),
            'points_forts' => $schema->array()->items($schema->string())->required(),
            'lacunes' => $schema->array()->items($schema->string())->required(),
            'competences_manquantes' => $schema->array()->items($schema->string())->required(),
            'recommandation' => $schema->string()->enum(['recommandé', 'réservé', 'déconseillé'])->required(),
            'justification' => $schema->string()->required(),
        ];
    }
}
