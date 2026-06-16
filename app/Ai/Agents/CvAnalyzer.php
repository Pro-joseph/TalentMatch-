<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class CvAnalyzer implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
Tu es un analyseur de CV spécialisé en recrutement technique.

À partir du texte du CV et de la description de l'offre d'emploi, tu dois :
1. Extraire les compétences techniques et non-techniques listées dans le CV
2. Déterminer le nombre d'années d'expérience totale
3. Identifier le plus haut niveau d'études atteint
4. Lister les langues parlées avec leur niveau
5. Calculer un score de matching (sur 100) entre le CV et l'offre
6. Identifier les points forts du candidat par rapport à l'offre
7. Identifier les lacunes du candidat par rapport à l'offre
8. Lister les compétences requises dans l'offre mais absentes du CV
9. Donner une recommandation : "recommandé", "réservé", ou "non_retenu"
10. Justifier brièvement la recommandation

Réponds UNIQUEMENT avec le JSON structuré ci-dessous, sans texte additionnel.
PROMPT;
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
            'competences_extraites' => $schema->array(
                $schema->string()
            )->description('Compétences techniques et non-techniques listées dans le CV'),
            'annees_experience' => $schema->integer()->description("Nombre d'années d'expérience totale"),
            'niveau_etudes' => $schema->string()->description("Plus haut niveau d'études atteint (ex: Licence, Master, Bac+5, Doctorat...)"),
            'langues' => $schema->array(
                $schema->object(
                    required: ['langue', 'niveau'],
                    properties: [
                        'langue' => $schema->string(),
                        'niveau' => $schema->string(),
                    ]
                )
            )->description('Langues parlées avec leur niveau'),
            'matching_score' => $schema->integer()->description('Score de matching sur 100'),
            'points_forts' => $schema->array(
                $schema->string()
            )->description('Points forts du candidat par rapport à l\'offre'),
            'lacunes' => $schema->array(
                $schema->string()
            )->description('Lacunes ou axes d\'amélioration'),
            'competences_manquantes' => $schema->array(
                $schema->string()
            )->description('Compétences requises dans l\'offre mais absentes du CV'),
            'recommandation' => $schema->enum(
                ['recommandé', 'réservé', 'non_retenu']
            )->description('Recommandation finale'),
            'justification' => $schema->string()->description('Brève justification de la recommandation'),
        ];
    }
}
