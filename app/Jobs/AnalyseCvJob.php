<?php

namespace App\Jobs;

use App\Ai\Agents\CvAnalyzer;
use App\Events\AnalysisCompleted;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AnalyseCvJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Offre $offre,
        public Candidat $candidat,
    ) {}

    public function handle(): void
    {
        $analyse = Analyse::updateOrCreate(
            [
                'offre_id' => $this->offre->id,
                'candidat_id' => $this->candidat->id,
            ],
            ['status' => 'pending']
        );

        try {
            $competences = is_array($this->offre->competences_requises)
                ? implode(', ', $this->offre->competences_requises)
                : $this->offre->competences_requises;

            $prompt = <<<PROMPT
Évalue dans quelle mesure ce candidat correspond à l'offre d'emploi.

OFFRE D'EMPLOI :
Titre : {$this->offre->titre}
Description : {$this->offre->description}
Compétences requises : {$competences}
Expérience min : {$this->offre->experience_min} an(s)

CV DU CANDIDAT :
{$this->candidat->cv_texte}

Compare le CV à l'offre ci-dessus. Calcule le score de matching, identifie les points forts et les lacunes par rapport à cette offre précise, et donne ta recommandation.
PROMPT;

            $response = (new CvAnalyzer)->prompt($prompt);

            $analyse->update([
                'competences_extraites' => $response['competences_extraites'] ?? null,
                'annees_experience' => $response['annees_experience'] ?? null,
                'niveau_etudes' => $response['niveau_etudes'] ?? null,
                'langues' => $response['langues'] ?? null,
                'matching_score' => $response['matching_score'] ?? null,
                'points_forts' => $response['points_forts'] ?? null,
                'lacunes' => $response['lacunes'] ?? null,
                'competences_manquantes' => $response['competences_manquantes'] ?? null,
                'recommandation' => $response['recommandation'] ?? null,
                'justification' => $response['justification'] ?? null,
                'status' => 'done',
            ]);

            event(new AnalysisCompleted($analyse));
        } catch (\Throwable $e) {
            $msg = $e->getMessage();

            $analyse->update([
                'status' => 'failed',
                'justification' => match (true) {
                    str_contains($msg, 'rate limit') || str_contains($msg, 'Rate limit') || str_contains($msg, 'RateLimited') => 'Limite de débit de l\'API IA atteinte. Veuillez patienter quelques instants puis relancer l\'analyse.',
                    str_contains($msg, 'overloaded') || str_contains($msg, 'Overloaded') => 'Le service d\'IA est temporairement surchargé. Veuillez réessayer plus tard.',
                    str_contains($msg, 'insufficient credits') || str_contains($msg, 'Insufficient') => 'Crédits IA insuffisants. Veuillez contacter l\'administrateur.',
                    default => 'L\'analyse a échoué pour une raison inattendue. Veuillez réessayer.',
                },
            ]);

            event(new AnalysisCompleted($analyse));
        }
    }
}
