# TalentMatch - Architecture & Décisions

## Stack
- **Laravel 13** + **PHP 8.4**
- **MySQL** (Docker), **Redis** (queue cache), **SQLite** (tests)
- **Laravel Reverb** (WebSocket temps réel)
- **Laravel AI SDK** (agents, structured output, tools, failover Groq→OpenAI)
- **Alpine.js** + **Tailwind CSS** (frontend minimal)
- **Docker Compose** : nginx, php-fpm, queue-worker, redis, mysql, reverb

## Architecture Agent

### Structured Output (HasStructuredOutput)
- `CvAnalyzer` : schéma JSON complet (matching_score, compétences, recommandation, etc.) → garantit que l'IA retourne des données typées
- `HrAssistant` : schéma avec `reponse` string + tools

### Tools
Les tools sont des classes PHP qui implémentent `Laravel\Ai\Contracts\Tool` :
- `GetJobRequirements` → lit une offre depuis la BDD
- `GetCandidateAnalysis` → lit l'analyse d'un candidat
- `CompareCandidates` → compare 2 candidats pour une offre

### Failover Provider
L'agent utilise `Lab::Grocery` qui tente Groq en premier, puis OpenAI en fallback si Groq rate ou rate-limit.

### Mémoire de conversation
Les messages sont stockés dans `agent_conversation_messages`. L'agent HR lit tout l'historique avant chaque réponse (`predict(...$previousMessages)`).

### Queue
`AnalyseCvJob` est dispatché sur la queue Redis → pas de blocage du frontend pendant l'analyse IA.

## Base de données
- `offres` : titre, description, competences_requises (JSON), experience_min
- `candidats` : nom, cv_texte (longText)
- `analyses` : offre_id, candidat_id, scores (JSON), recommandation (enum)
- `agent_conversations` / `agent_conversation_messages` : gérées par laravel/ai

## Temps réel
- `AnalysisCompleted` → broadcast sur private-analyses.{id} → page de résultat se recharge
- `ConversationMessageAdded` → broadcast sur private-conversations.{id} → nouveaux messages apparaissent

## Docker
- Volumes séparés pour `/var/www/vendor` par service (php, queue-worker, reverb)
- Les dépendances doivent être installées dans chaque container ou l'image rebuildée

## Tests
- Pest PHP
- Base SQLite en mémoire
- 38 tests couvrant analyse, assistant, offres
