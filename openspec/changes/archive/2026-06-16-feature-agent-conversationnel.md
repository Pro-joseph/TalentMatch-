# Agent conversationnel RH

**Date :** 2026-06-16
**Branch :** `feature/agent-conversationnel`
**Commit :** `f9d6143`

## Changements

### Controller

- **`app/Http/Controllers/AgentConversationController.php`**
  - `index()` — liste paginée des conversations de l'utilisateur
  - `create()` / `store()` — nouvelle conversation (sujet + premier message)
  - `show()` — affiche la conversation avec historique des messages + formulaire d'envoi
  - `message()` — traite un nouveau message : sauvegarde en BDD, appelle l'IA, sauvegarde la réponse
  - `destroy()` — supprime conversation + ses messages
  - Utilise `AnonymousAgent` pour créer un agent ad-hoc avec l'historique chargé depuis `agent_conversation_messages`
  - Injection des instructions et outils de `HrAssistant` dans l'agent anonyme

### Vues

- **`resources/views/agent-conversations/index.blade.php`** — liste des conversations
- **`resources/views/agent-conversations/create.blade.php`** — formulaire sujet + premier message
- **`resources/views/agent-conversations/show.blade.php`** — interface de chat : historique déroulant + champ de saisie

### Routes

```
GET       /assistant              → agent-conversations.index
GET       /assistant/creer        → agent-conversations.create
POST      /assistant              → agent-conversations.store
GET       /assistant/{id}         → agent-conversations.show
POST      /assistant/{id}/message → agent-conversations.message
DELETE    /assistant/{id}         → agent-conversations.destroy
```

### Navigation

- Lien « Assistant RH » ajouté dans `layouts/navigation.blade.php` (desktop + mobile)

## Fonctionnement

1. L'utilisateur crée une conversation avec un sujet et un message initial
2. Le message est sauvegardé dans `agent_conversation_messages` (role: user)
3. Le controller charge tout l'historique de la conversation
4. Crée un `AnonymousAgent` avec instructions HrAssistant + outils (GetJobRequirements, GetCandidateAnalysis, CompareCandidates) + messages historiques
5. Appelle `$agent->prompt(nouveauMessage)` → l'IA répond avec analyse contextuelle
6. La réponse (role: assistant) est sauvegardée avec les tool_calls et tool_results
7. L'interface affiche la conversation en temps réel (scroll automatique)
