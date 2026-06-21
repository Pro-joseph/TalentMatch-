<?php

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
});

it('redirects to login for unauthenticated users', function () {
    $offre = Offre::create([
        'titre' => 'Test',
        'description' => 'Desc',
        'competences_requises' => ['PHP'],
        'user_id' => User::factory()->create()->id,
    ]);

    $this->get(route('offres.comparer', $offre))
        ->assertRedirect('/login');
});

it('shows all analyses sorted by score descending when no candidats param', function () {
    $offre = Offre::create([
        'titre' => 'Développeur Laravel',
        'description' => 'Description du poste',
        'competences_requises' => ['PHP', 'Laravel', 'SQL'],
        'experience_min' => 2,
        'user_id' => $this->user->id,
    ]);

    $candidats = [];
    foreach (['Jean', 'Marie', 'Paul'] as $nom) {
        $candidats[] = Candidat::create([
            'nom' => $nom,
            'cv_texte' => 'CV texte',
            'user_id' => $this->user->id,
        ]);
    }

    Analyse::create([
        'offre_id' => $offre->id,
        'candidat_id' => $candidats[0]->id,
        'matching_score' => 50,
        'status' => 'done',
        'recommandation' => Recommandation::Reserve,
    ]);
    Analyse::create([
        'offre_id' => $offre->id,
        'candidat_id' => $candidats[1]->id,
        'matching_score' => 85,
        'status' => 'done',
        'recommandation' => Recommandation::Recommande,
    ]);
    Analyse::create([
        'offre_id' => $offre->id,
        'candidat_id' => $candidats[2]->id,
        'matching_score' => 30,
        'status' => 'done',
        'recommandation' => Recommandation::NonRetenu,
    ]);

    actingAs($this->user)
        ->get(route('offres.comparer', $offre))
        ->assertOk()
        ->assertSeeInOrder(['85', '50', '30']);
});

it('filters by specific candidats param', function () {
    $offre = Offre::create([
        'titre' => 'Développeur Laravel',
        'description' => 'Description',
        'competences_requises' => ['PHP'],
        'user_id' => $this->user->id,
    ]);

    $candidats = [];
    for ($i = 0; $i < 3; $i++) {
        $candidats[] = Candidat::create([
            'nom' => "Candidat $i",
            'cv_texte' => 'CV',
            'user_id' => $this->user->id,
        ]);
    }

    foreach ($candidats as $c) {
        Analyse::create([
            'offre_id' => $offre->id,
            'candidat_id' => $c->id,
            'matching_score' => rand(0, 100),
            'status' => 'done',
        ]);
    }

    actingAs($this->user)
        ->get(route('offres.comparer', [
            'offre' => $offre,
            'candidats' => [$candidats[0]->id, $candidats[1]->id],
        ]))
        ->assertOk();
});

it('requires at least 2 candidats in param', function () {
    $offre = Offre::create([
        'titre' => 'Test',
        'description' => 'Desc',
        'competences_requises' => ['PHP'],
        'user_id' => $this->user->id,
    ]);
    $candidat = Candidat::create([
        'nom' => 'Candidat',
        'cv_texte' => 'CV',
        'user_id' => $this->user->id,
    ]);

    Analyse::create([
        'offre_id' => $offre->id,
        'candidat_id' => $candidat->id,
        'status' => 'done',
    ]);

    actingAs($this->user)
        ->get(route('offres.comparer', [
            'offre' => $offre,
            'candidats' => [$candidat->id],
        ]))
        ->assertSessionHasErrors('candidats');
});

it('shows compare button on offer show page when 2+ analyses exist', function () {
    $offre = Offre::create([
        'titre' => 'Test',
        'description' => 'Desc',
        'competences_requises' => ['PHP'],
        'user_id' => $this->user->id,
    ]);

    $candidats = [];
    for ($i = 0; $i < 2; $i++) {
        $c = Candidat::create(['nom' => "C$i", 'cv_texte' => 'CV', 'user_id' => $this->user->id]);
        $candidats[] = $c;
        Analyse::create([
            'offre_id' => $offre->id,
            'candidat_id' => $c->id,
            'status' => 'done',
        ]);
    }

    actingAs($this->user)
        ->get(route('offres.show', $offre))
        ->assertOk()
        ->assertSee('Comparer');
});

it('hides compare button when fewer than 2 analyses', function () {
    $offre = Offre::create([
        'titre' => 'Test',
        'description' => 'Desc',
        'competences_requises' => ['PHP'],
        'user_id' => $this->user->id,
    ]);
    $c = Candidat::create(['nom' => 'C1', 'cv_texte' => 'CV', 'user_id' => $this->user->id]);
    Analyse::create([
        'offre_id' => $offre->id,
        'candidat_id' => $c->id,
        'status' => 'done',
    ]);

    actingAs($this->user)
        ->get(route('offres.show', $offre))
        ->assertOk()
        ->assertDontSee('Comparer');
});
