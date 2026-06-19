# TalentMatch

Application web de matching CV-offre d'emploi avec analyse IA. Gère les offres, les candidats, et l'analyse automatique des CV via l'API Groq.

## Architecture MVC

| Couche | Composants |
|--------|-----------|
| **Models** | `Offre`, `Candidat`, `Analyse`, `User` |
| **Controllers** | `OffreController`, `CandidatController`, `AnalyseController`, `DashboardController`, `AgentConversationController` |
| **Views** | Blade + Tailwind CSS 3 + Alpine.js |
| **AI agents** | `ComparativeAnalyzer`, `HrAssistant` + outils (`GetCandidateAnalysis`, `CompareCandidates`, `GetJobRequirements`) |
| **Jobs** | `AnalyseCvJob` (file d'attente Redis) |

## Fonctionnalités

- **Offres d'emploi** — CRUD complet avec titre, description, compétences requises, expérience minimum
- **Candidats** — Ajout de CV (texte) pour chaque candidat
- **Analyse IA** — Analyse automatique des CV via Groq (matching score, compétences extraites, expérience, niveau d'études, forces/faiblesses, recommandation)
- **Comparaison** — Tableau comparatif de 2 à 15 candidats, classé par score, avec verdict IA généré à la demande
- **Assistant RH** — Chat conversationnel avec agent IA pouvant consulter les offres, analyses et comparer des candidats
- **Dashboard** — Statistiques : nombre d'offres, candidats, analyses effectuées, score moyen

## Stack technique

- **Backend** : PHP 8.4, Laravel 13, MySQL/Redis
- **Frontend** : Blade, Tailwind CSS 3, Alpine.js, Vite
- **IA** : `laravel/ai` SDK (fournisseur Groq, modèle `openai/gpt-oss-120b`)
- **File d'attente** : Redis (analyse asynchrone des CV)
- **Tests** : Pest PHP 4

## Prérequis

- PHP 8.3+
- Composer
- Node.js 20+
- MySQL ou SQLite
- Redis (pour la file d'attente)
- Clé API Groq (https://console.groq.com)

## Installation

```bash
# 1. Cloner le dépôt
git clone <url-du-depot> talentmatch
cd talentmatch

# 2. Installer les dépendances PHP
composer install

# 3. Configuration
cp .env.example .env
php artisan key:generate

# 4. Configurer .env
#    - Base de données (DB_HOST, DB_DATABASE, etc.)
#    - Redis (QUEUE_CONNECTION=redis, REDIS_HOST, etc.)
#    - Clé API Groq (GROQ_API_KEY)

# 5. Migrations
php artisan migrate

# 6. Installer les dépendances frontend
npm install
npm run build

# 7. Lancer l'application (4 processus simultanés)
composer run dev
#   → Serveur HTTP : http://localhost:8000
#   → File d'attente : queue:listen
#   → Logs : pail
#   → Vite : hot reload
```

## Tests

```bash
php artisan test
```

## Structure du projet (app/)

```
app/
├── Ai/
│   ├── Agents/
│   │   ├── ComparativeAnalyzer.php   # Agent pour verdict comparatif
│   │   └── HrAssistant.php           # Agent assistant RH conversationnel
│   └── Tools/
│       ├── CompareCandidates.php      # Outil de comparaison
│       ├── GetCandidateAnalysis.php   # Récupération d'analyse
│       └── GetJobRequirements.php     # Consultation d'offre
├── Http/
│   ├── Controllers/
│   │   ├── OffreController.php
│   │   ├── CandidatController.php
│   │   ├── AnalyseController.php
│   │   ├── DashboardController.php
│   │   └── AgentConversationController.php
│   └── Requests/
│       └── StoreOffreRequest.php
├── Jobs/
│   └── AnalyseCvJob.php               # Analyse asynchrone CV→IA
└── Models/
    ├── Offre.php
    ├── Candidat.php
    ├── Analyse.php
    └── User.php
```

## Captures d'écran (idées)

> *Ajoutez ici des captures de l'interface — dashboard, fiche offre, comparaison, assistant RH.*

## Licence

MIT
