<?php

namespace App\Jobs;

use App\Ai\Agents\CvAnalyzer;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Laravel\Ai\Facades\Ai;

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
            $prompt = <<<PROMPT
CV du candidat :
{$this->candidat->cv_texte}

Offre d'emploi :
Titre : {$this->offre->titre}
Description : {$this->offre->description}
Compétences requises : {$this->offre->competences_requises}
Expérience min : {$this->offre->experience_min} an(s)
PROMPT;

            $result = Ai::chat()
                ->agent(new CvAnalyzer)
                ->messages($prompt)
                ->execute();

            $analyse->update([
                'competences_extraites' => $result['competences_extraites'] ?? null,
                'annees_experience' => $result['annees_experience'] ?? null,
                'niveau_etudes' => $result['niveau_etudes'] ?? null,
                'langues' => $result['langues'] ?? null,
                'matching_score' => $result['matching_score'] ?? null,
                'points_forts' => $result['points_forts'] ?? null,
                'lacunes' => $result['lacunes'] ?? null,
                'competences_manquantes' => $result['competences_manquantes'] ?? null,
                'recommandation' => $result['recommandation'] ?? null,
                'justification' => $result['justification'] ?? null,
                'status' => 'done',
            ]);
        } catch (\Throwable $e) {
            $analyse->update([
                'status' => 'failed',
                'justification' => $e->getMessage(),
            ]);
        }
    }
}
