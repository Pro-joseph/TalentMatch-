<?php

use App\Ai\Agents\ComparativeAnalyzer;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;
use App\Recommandation;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->offre = Offre::create([
        'titre' => 'Développeur Laravel',
        'description' => 'Développement d\'applications web.',
        'competences_requises' => ['PHP', 'Laravel', 'SQL'],
        'experience_min' => 2,
        'user_id' => $this->user->id,
    ]);

    $candidats = [];
    foreach (['Alice', 'Bob', 'Charlie'] as $nom) {
        $c = Candidat::create(['nom' => $nom, 'cv_texte' => 'CV de '.$nom, 'user_id' => $this->user->id]);
        $candidats[] = $c;
        Analyse::create([
            'offre_id' => $this->offre->id,
            'candidat_id' => $c->id,
            'matching_score' => $nom === 'Alice' ? 90 : ($nom === 'Bob' ? 70 : 40),
            'status' => 'done',
            'recommandation' => $nom === 'Alice' ? Recommandation::Recommande : ($nom === 'Bob' ? Recommandation::Reserve : Recommandation::NonRetenu),
            'points_forts' => $nom === 'Alice' ? ['Expert Laravel', 'Senior'] : ($nom === 'Bob' ? ['PHP solide'] : []),
            'lacunes' => $nom === 'Bob' ? ['Pas de SQL'] : ($nom === 'Charlie' ? ['Pas de PHP', 'Junior'] : []),
            'competences_manquantes' => $nom === 'Bob' ? ['SQL'] : ($nom === 'Charlie' ? ['PHP', 'Laravel', 'SQL'] : []),
            'competences_extraites' => $nom === 'Alice' ? ['PHP', 'Laravel', 'SQL', 'Docker'] : ($nom === 'Bob' ? ['PHP', 'JavaScript'] : ['HTML', 'CSS']),
            'annees_experience' => $nom === 'Alice' ? 8 : ($nom === 'Bob' ? 3 : 1),
            'niveau_etudes' => $nom === 'Alice' ? 'Master' : ($nom === 'Bob' ? 'Licence' : 'Bac'),
            'justification' => 'Justification pour '.$nom,
        ]);
    }
});

it('generates comparative verdict successfully', function () {
    ComparativeAnalyzer::fake([
        [
            'rankings' => [
                ['nom' => 'Alice', 'score' => 90, 'forces' => ['Expert Laravel'], 'faiblesses' => []],
                ['nom' => 'Bob', 'score' => 70, 'forces' => ['PHP solide'], 'faiblesses' => ['Pas de SQL']],
                ['nom' => 'Charlie', 'score' => 40, 'forces' => [], 'faiblesses' => ['Pas de PHP', 'Junior']],
            ],
            'analyse' => 'Alice est clairement la meilleure candidate.',
            'recommandation' => 'Recruter Alice en priorité.',
        ],
    ]);

    $response = actingAs($this->user)
        ->postJson(route('offres.comparer.verdict', $this->offre), [
            'analyse_ids' => $this->offre->analyses->pluck('id')->toArray(),
        ]);

    $response->assertOk()
        ->assertJsonStructure([
            'rankings' => [
                '*' => ['nom', 'score', 'forces', 'faiblesses'],
            ],
            'analyse',
            'recommandation',
        ]);
});

it('returns error when analyses are pending', function () {
    $analyse = $this->offre->analyses()->first();
    $analyse->update(['status' => 'pending']);

    $response = actingAs($this->user)
        ->postJson(route('offres.comparer.verdict', $this->offre), [
            'analyse_ids' => $this->offre->analyses->pluck('id')->toArray(),
        ]);

    $response->assertStatus(422)
        ->assertJsonPath('error', fn ($e) => str_contains($e, 'encore en cours'));
});

it('returns error when analyses are failed', function () {
    $analyse = $this->offre->analyses()->first();
    $analyse->update(['status' => 'failed']);

    $response = actingAs($this->user)
        ->postJson(route('offres.comparer.verdict', $this->offre), [
            'analyse_ids' => $this->offre->analyses->pluck('id')->toArray(),
        ]);

    $response->assertStatus(422)
        ->assertJsonPath('error', fn ($e) => str_contains($e, 'a échoué'));
});

it('rejects invalid analyse_ids', function () {
    actingAs($this->user)
        ->postJson(route('offres.comparer.verdict', $this->offre), ['analyse_ids' => $this->offre->analyses->pluck('id')->toArray()])
        ->assertOk()
        ->assertJsonStructure(['rankings', 'analyse', 'recommandation']);
});
