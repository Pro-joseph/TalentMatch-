<?php

use App\Ai\Agents\CvAnalyzer;
use App\Jobs\AnalyseCvJob;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->offre = Offre::create([
        'titre' => 'Développeur Laravel',
        'description' => 'Créer et maintenir des applications web avec Laravel.',
        'competences_requises' => ['PHP', 'Laravel', 'SQL', 'JavaScript'],
        'experience_min' => 2,
        'user_id' => $this->user->id,
    ]);

    $this->candidat = Candidat::create([
        'nom' => 'Jean Dupont',
        'cv_texte' => '5 ans d\'expérience en PHP et Laravel. Maîtrise de SQL et JavaScript.',
        'user_id' => $this->user->id,
    ]);
});

it('creates analysis record and marks as done on success', function () {
    CvAnalyzer::fake([
        [
            'matching_score' => 85,
            'points_forts' => ['Bonne maîtrise de Laravel', 'Expérience en PHP'],
            'lacunes' => ['Pas de Docker'],
            'competences_manquantes' => ['Docker'],
            'recommandation' => 'recommandé',
            'justification' => 'Profil solide pour le poste.',
            'competences_extraites' => ['PHP', 'Laravel', 'SQL', 'JavaScript'],
            'annees_experience' => 5,
            'niveau_etudes' => 'Master',
            'langues' => [['langue' => 'Français', 'niveau' => 'Natif']],
        ],
    ]);

    (new AnalyseCvJob($this->offre, $this->candidat))->handle();

    $analyse = Analyse::where('offre_id', $this->offre->id)
        ->where('candidat_id', $this->candidat->id)
        ->first();

    expect($analyse)->not->toBeNull();
    expect($analyse->status)->toBe('done');
    expect($analyse->matching_score)->toBe(85);
    expect($analyse->recommandation->value)->toBe('recommandé');
});

it('handles gracefully and creates analysis even when handle is called', function () {
    CvAnalyzer::fake([
        [
            'matching_score' => 65,
            'points_forts' => [],
            'lacunes' => [],
            'competences_manquantes' => [],
            'recommandation' => 'réservé',
            'justification' => 'Profil moyen.',
            'competences_extraites' => [],
            'annees_experience' => 2,
            'niveau_etudes' => 'Licence',
            'langues' => [],
        ],
    ]);

    AnalyseCvJob::dispatch($this->offre, $this->candidat);

    $analyse = Analyse::where('offre_id', $this->offre->id)
        ->where('candidat_id', $this->candidat->id)
        ->first();

    expect($analyse)->not->toBeNull();
    expect($analyse->status)->toBe('done');
});
