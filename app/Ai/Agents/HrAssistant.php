<?php

namespace App\Ai\Agents;

use App\Ai\Tools\CompareCandidates;
use App\Ai\Tools\GetCandidateAnalysis;
use App\Ai\Tools\GetJobRequirements;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Stringable;

class HrAssistant implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
Tu es un assistant RH spécialisé dans l'analyse et la comparaison de candidats.

Tu as accès à des outils pour :
1. Consulter les exigences d'une offre d'emploi (GetJobRequirements)
2. Voir l'analyse d'un candidat pour une offre (GetCandidateAnalysis)
3. Comparer deux candidats pour une offre (CompareCandidates)

Utilise ces outils quand l'utilisateur te pose des questions sur les offres, les candidats ou les analyses.
Réponds en français, de manière claire et professionnelle.
INSTRUCTIONS;
    }

    public function messages(): iterable
    {
        return [];
    }

    public function tools(): iterable
    {
        return [
            new GetJobRequirements,
            new GetCandidateAnalysis,
            new CompareCandidates,
        ];
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'reponse' => $schema->string(),
        ];
    }
}
