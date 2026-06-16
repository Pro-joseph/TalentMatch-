# Analyse IA des CV

**Date :** 2026-06-16
**Branch :** `feature/analyse-ia`
**Commit :** `fa297f5`

## Changements

### Modèles

- **`app/Models/Candidat.php`** — fillable (nom, cv_texte, user_id), relations `belongsTo:user`, `hasMany:analyses`
- **`app/Models/Analyse.php`** — fillable, casts JSON → array, enum `Recommandation`, relations `belongsTo:offre`, `belongsTo:candidat`
- **`app/Recommandation.php`** — enum backed string : `recommandé`, `réservé`, `non_retenu`
- **`app/Models/Offre.php`** — ajout relation `hasMany:analyses`
- **`app/Models/User.php`** — ajout relation `hasMany:candidats`

### AI Agents (via `laravel/ai`)

- **`app/Ai/Agents/CvAnalyzer.php`** — prompt détaillé pour extraction structurée des CV (compétences, exp, études, langues, matching, recommandation) ; schéma JSON structuré complet
- **`app/Ai/Agents/HrAssistant.php`** — assistant RH avec 3 outils (GetJobRequirements, GetCandidateAnalysis, CompareCandidates)

### AI Tools

- **`app/Ai/Tools/GetJobRequirements.php`** — récupère titre, description, compétences, exp min d'une offre
- **`app/Ai/Tools/GetCandidateAnalysis.php`** — récupère le résultat complet d'une analyse
- **`app/Ai/Tools/CompareCandidates.php`** — compare plusieurs candidats pour une offre (score, exp, études, recommandations)

### Job asynchrone

- **`app/Jobs/AnalyseCvJob.php`** — prend offre + candidat, envoie le prompt à l'agent CvAnalyzer via `Ai::chat()`, sauvegarde le résultat dans `analyses`, gère les erreurs

### Controllers

- **`app/Http/Controllers/CandidatController.php`** — CRUD complet avec pagination, validations, messages flash
- **`app/Http/Controllers/AnalyseController.php`** — index (analyses d'une offre), show (résultat détaillé), store (lancer analyse avec dispatch du job)
- **`app/Policies/CandidatPolicy.php`** — autorisation par propriétaire

### Vues

- **`resources/views/candidats/`** — index (liste paginée), create (nom + CV texte), edit, show (CV + analyses liées)
- **`resources/views/analyses/`** — index (score, exp, niveau, recommandation avec badges), show (matching %, points forts/lacunes, compétences, langues, etc.)
- **`resources/views/offres/show.blade.php`** — ajout bouton "Analyses" + formulaire pour lancer analyse sur un candidat
- **`resources/views/layouts/navigation.blade.php`** — lien "Candidats" ajouté

### Routes

```
GET    /candidats                   → candidats.index
POST   /candidats                   → candidats.store
GET    /candidats/create            → candidats.create
GET    /candidats/{candidat}        → candidats.show
PUT    /candidats/{candidat}        → candidats.update
DELETE /candidats/{candidat}        → candidats.destroy
GET    /candidats/{candidat}/edit   → candidats.edit

POST   /offres/{offre}/analyser     → analyses.store (lancer analyse)
GET    /offres/{offre}/analyses     → analyses.index
GET    /analyses/{analyse}          → analyses.show
```

## Dépendances

- `laravel/ai` (v0.8.1) — package AI
- `GROQ_API_KEY` dans .env — clé API Groq pour l'inférence
- Redis pour la queue asynchrone
