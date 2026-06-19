<?php

namespace App\Jobs;

use App\Ai\Agents\CvAnalyzer;
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
        } catch (\Throwable $e) {
            $analyse->update([
                'status' => 'failed',
                'justification' => $e->getMessage()."\n".$e->getTraceAsString(),
            ]);
        }
    }
}
