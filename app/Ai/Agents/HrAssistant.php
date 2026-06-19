<?php

namespace App\Ai\Agents;

use App\Ai\Tools\CompareCandidates;
use App\Ai\Tools\GetCandidateAnalysis;
use App\Ai\Tools\GetJobRequirements;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Stringable;

class HrAssistant implements Agent, Conversational, HasTools
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
Tu es un assistant RH spécialisé dans l'analyse de CV et le matching candidat-offre.

Tu disposes d'outils pour :
- Consulter les exigences d'une offre (GetJobRequirements)
- Consulter le résultat d'une analyse (GetCandidateAnalysis)
- Comparer plusieurs candidats pour une offre (CompareCandidates)

Réponds en français aux questions des recruteurs de manière claire et concise.
PROMPT;
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
}
