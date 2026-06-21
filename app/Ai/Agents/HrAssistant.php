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

Quand un recruteur te demande des informations sur un candidat ou une comparaison :
1. Utilise d'abord les outils disponibles pour récupérer les données.
2. Si un candidat n'a pas encore été analysé (statut "pending"), les outils lanceront automatiquement l'analyse. Informe le recruteur que l'analyse est en cours et qu'il devra revenir plus tard.
3. Si une analyse a échoué (statut "failed"), informe le recruteur avec un message clair et propose de soumettre à nouveau le CV.
4. Si les analyses sont disponibles, présente les résultats de manière claire et concise.

Réponds en français aux questions des recruteurs de manière claire et concise, avec un ton professionnel mais accessible.
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
