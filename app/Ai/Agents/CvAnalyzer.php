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
        return <<<'PROMPT'
Tu es un expert en matching CV-offre d'emploi. Ton rôle est d'évaluer dans quelle mesure un CV correspond aux exigences d'une offre.

À partir de la description de l'offre et du CV du candidat, tu dois :
1. Calculer un score de matching (sur 100) — à quel point le CV répond aux requis de l'offre
2. Lister les points forts du candidat spécifiquement par rapport à l'offre
3. Lister les lacunes du candidat par rapport à l'offre
4. Lister les compétences requises dans l'offre mais absentes du CV
5. Donner une recommandation : "recommandé", "réservé", ou "non_retenu"
6. Justifier brièvement la recommandation
7. Extraire les compétences techniques et non-techniques listées dans le CV (utile pour comparer les candidats entre eux)
8. Déterminer le nombre d'années d'expérience totale
9. Identifier le plus haut niveau d'études atteint
10. Lister les langues parlées avec leur niveau

La priorité est l'évaluation du matching (points 1 à 6). Les points 7 à 10 sont des informations complémentaires pour la comparaison entre candidats.

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
            'matching_score' => $schema->integer()->required()->description('Score de matching CV vs offre sur 100'),
            'points_forts' => $schema->array(
                $schema->string()->required()
            )->required()->description('Atouts du candidat qui répondent aux exigences de l\'offre'),
            'lacunes' => $schema->array(
                $schema->string()->required()
            )->required()->description('Écarts entre le profil du candidat et les requis de l\'offre'),
            'competences_manquantes' => $schema->array(
                $schema->string()->required()
            )->required()->description('Compétences demandées dans l\'offre mais absentes du CV'),
            'recommandation' => $schema->string()->required()->enum(
                ['recommandé', 'réservé', 'non_retenu']
            )->description('Avis final sur le candidat pour cette offre'),
            'justification' => $schema->string()->required()->description('Brève justification de la recommandation'),
            'competences_extraites' => $schema->array(
                $schema->string()->required()
            )->required()->description('Toutes les compétences (techniques et non-techniques) trouvées dans le CV — pour comparer les candidats'),
            'annees_experience' => $schema->integer()->required()->description("Années d'expérience totale du candidat"),
            'niveau_etudes' => $schema->string()->required()->description('Plus haut diplôme du candidat (ex: Licence, Master, Bac+5, Doctorat...)'),
            'langues' => $schema->array(
                $schema->object([
                    'langue' => $schema->string()->required(),
                    'niveau' => $schema->string()->required(),
                ])
            )->required()->description('Langues parlées par le candidat avec leur niveau'),
        ];
    }
}
