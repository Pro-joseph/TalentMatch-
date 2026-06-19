## Context

TalentMatch backend is complete — Offre, Candidat, Analyse models with Eloquent relationships, AI agents powered by `laravel/ai` (Groq/llama-3.3-70b), async CV analysis via `AnalyseCvJob` on Redis queue, and a persisted conversational assistant. The frontend uses Laravel Breeze (Blade + Tailwind 3 + Alpine.js). All CRUD routes and controllers exist but use generic scaffolding.

The redesign targets HR professionals at Moroccan startups: mobile-first, visually sober, with immediate readability of candidate scores and recommendations.

## Goals / Non-Goals

**Goals:**
- Redesign 5 existing views (offres/index, offres/create, offres/show, analyses/show, agent-conversations/show) with a consistent design language
- Create 3 new views (dashboard landing, CV submission with polling, candidate comparison)
- Build reusable Blade components for score visualization, recommendation badges, and skill tags
- Implement Alpine.js polling for async CV analysis feedback
- Ensure full mobile/desktop responsiveness
- Keep accessibility baseline (labels, contrast, focus-visible)

**Non-Goals:**
- No backend logic changes (controllers stay RESTful, no new endpoints beyond routing)
- No database schema changes
- No external JS libraries (no chart.js, no date picker libs — use native HTML + Alpine)
- No authentication or authorization changes

## Decisions

### 1. Layout architecture: leverage existing Breeze layout, no new shell

Keep `layouts/app.blade.php` and `navigation.blade.php` as-is. Add dashboard link to nav. The existing x-app-layout component provides consistent header, nav, and content area. No new layout shell needed.

### 2. Score visualization: SVG ring for detail, Tailwind bar for lists

- **Detail views** (analysis show, comparison): Inline SVG ring (donut arc) — pure Tailwind stroke colors, no chart library. The SVG approach gives a polished, compact visual that fits next to candidate info.
- **List views** (offer detail candidate table): Simple Tailwind `<div>` progress bar — lighter markup, works in constrained table cells, sorts by score descending.
- Color thresholds via Blade: `@php($color = $score >= 70 ? 'emerald' : ($score >= 40 ? 'amber' : 'red'))` then applied to stroke/fill classes.
- Alternative considered: ApexCharts — rejected for bundle size and scope. Two visual primitives cover all cases.

### 3. Recommendation badges: single Blade component

A `<x-recommendation-badge :recommandation="$analyse->recommandation" />` component that renders a color-coded pill:
- `convoquer` → emerald bg/text/border
- `attente` → amber bg/text/border
- `rejeter` → red bg/text/border
Maps directly to the `App\Enums\Recommandation` backed enum (French values).

### 4. CV submission polling: Alpine.js `$watch` + `setInterval`

No WebSockets or Laravel Echo — overkill for a single-user polling scenario. Approach:
1. Form POST to `offres/{offre}/analyser` returns the new `Analyse` ID
2. Alpine component `x-data="{ status: 'pending', analyseId: null }"` starts `setInterval` to `GET /analyses/{id}/status` every 2 seconds
3. On status `completed`, redirect to analysis detail or unmask the results inline
4. Show a spinner + "Analyse en cours…" message during polling
- Alternative considered: SSE / Laravel Reverb — too complex for this use case. The User Experience is identical (spinner + update).

### 5. Skill tags input: Alpine-driven Blade component

A `<x-skill-input name="competences_requises" :existing="old('competences_requises', $offre->competences_requises ?? [])" />` component using Alpine.js:
- Input field captures text, creates badge on Enter or comma
- Each badge has a × button to remove
- Values stored in a hidden JSON field serialized on form submit
- No JS libraries — ~40 lines of inline Alpine

### 6. Candidate comparison: single route with query params

- Route: `GET /offres/{offre}/comparer?candidats[]=uuid1&candidats[]=uuid2`
- A new method `comparer()` on `OffreController` fetches both analyses, passes to a dedicated view
- View uses CSS grid: `grid-cols-1 md:grid-cols-2 gap-6`
- Each column is a mini analysis detail (score ring, extracted info, strengths/weaknesses, competence gaps)
- Bottom section: agent verdict via `HrAssistant` tool call or a hardcoded comparison of the two JSON structures
- Alternative considered: dedicated ComparisonController — rejected. Single method on OffreController keeps routing simple.

### 7. Assistant integration: modal/sidebar from analysis show

- Analysis detail view gets a "Poser une question" button that opens a modal (using the existing `<x-modal>` component)
- Modal loads a minimal chat: message list + input
- Messages POST to `agent-conversations.message` with a pre-filled context prompt about the candidate
- Tool-call badges: when assistant calls a tool, show a small grey badge: "🔍 Consulte les données du candidat…"
- Alternative considered: persistent sidebar — rejected on mobile. A centered modal works on all viewports.

### 8. Mobile strategy: table → card pattern

- All `<table>` elements get a responsive wrapper: `overflow-x-auto` on `table-wrap`
- On `sm` breakpoint, tables remain as tables with horizontal scroll
- Candidate table on offer detail: on `xs` screens, rows collapse into stacked cards using a responsive pattern (hide `<thead>`, render each `<tr>` as a card via `hidden sm:table-row` / `sm:hidden` card markup)
- Dashboard metrics use `grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`

### 9. New routes and controller changes

```
GET  /dashboard                         → DashboardController@index      (new)
POST /offres/{offre}/soumettre-cv       → OffreController@submitCv      (new)
GET  /analyses/{analyse}/status         → AnalyseController@status      (new, JSON)
GET  /offres/{offre}/comparer           → OffreController@comparer      (new)
```

No route changes to existing CRUD paths.

## Risks / Trade-offs

| Risk | Mitigation |
|------|-----------|
| Polling creates 2s-lagged status updates | Acceptable for a background job that takes 5-30s. User sees a spinner. |
| No WebSockets means page reload needed for fresh data | All screens use standard GET requests. The assistant chat is the only interactive component, and it works via standard form POST + redirect. |
| SVG score ring doesn't render in very old email clients | N/A — this is a web app, not email. Falls back to plain text for screen readers (`<span class="sr-only">Score: 85</span>`). |
| Inline Alpine.js in Blade components increases view size | Acceptable. Each component is <50 lines. Better than importing a JS framework for two interactive elements. |

## Migration Plan

1. Create Blade components first (score-ring, score-bar, recommendation-badge, skill-input) — they are dependencies for views
2. Build dashboard view and controller
3. Redesign offres/index, offres/create, offres/show
4. Build CV submission view with polling
5. Redesign analyses/show
6. Build candidate comparison view
7. Add assistant integration to analysis detail
8. Polish layout, navigation, mobile responsiveness
9. Run existing test suite to verify no backend breakage

Rollback: revert changed views to previous commit. No migrations to roll back.

## Open Questions

- Should the dashboard use the existing `offres/index` route or a new `/dashboard` path? → Decision: new `/dashboard` to keep the offers list as a dedicated resource and avoid breaking existing links.
- Comparison: should the agent verdict be real-time (AI call) or cached (stored in session/DB)? → Decision: real-time AI call on page load, with a loading state, since comparisons are ad-hoc.
