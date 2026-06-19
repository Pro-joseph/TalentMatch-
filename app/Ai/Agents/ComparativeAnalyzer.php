<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class ComparativeAnalyzer implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
Tu es un consultant RH expert en analyse comparative de candidats pour une offre d'emploi.

On te donne :
- Les détails d'une offre d'emploi (titre, description, compétences requises, expérience min)
- Les analyses individuelles de plusieurs candidats (score, expérience, études, compétences, points forts, lacunes, recommandation)

Tu dois :
1. Classer les candidats du meilleur au moins bon pour cette offre
2. Justifier ton classement en comparant leurs forces et faiblesses respectives
3. Recommander les 4 à 6 meilleurs candidats (ou moins s'il y en a moins)
4. Expliquer pourquoi les candidats retenus sont les plus adaptés
5. Donner un verdict global clair et actionable pour le recruteur

Réponds UNIQUEMENT avec le JSON structuré ci-dessous, sans texte additionnel.
PROMPT;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'rankings' => $schema->array(
                $schema->object([
                    'nom' => $schema->string()->required()->description('Nom du candidat'),
                    'score' => $schema->integer()->required()->description('Score de matching sur 100'),
                    'forces' => $schema->array($schema->string())->required()->description('Principaux points forts pour cette offre'),
                    'faiblesses' => $schema->array($schema->string())->required()->description('Principales lacunes par rapport à l\'offre'),
                ])
            )->required()->description('Classement des candidats du meilleur au moins bon'),
            'analyse' => $schema->string()->required()->description('Analyse comparative détaillée justifiant le classement'),
            'recommandation' => $schema->string()->required()->description('Recommandation actionable pour le recruteur : quels candidats retenir et pourquoi'),
        ];
    }
}
