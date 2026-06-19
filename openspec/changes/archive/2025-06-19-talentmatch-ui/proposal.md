## Why

TalentMatch has a fully functional backend (models, agents, jobs, enums) and Breeze-scaffolded CRUD views, but the UI is still raw Laravel scaffolding — not tailored for the target users (HR professionals in Moroccan startups). The current views lack visual hierarchy, mobile responsiveness, real-time feedback for async AI analysis, and the polished UX needed for a production-ready pre-screening tool.

## What Changes

- Redesign the **Offers list** as a proper landing dashboard with metrics and visual hierarchy
- Redesign the **Offer creation form** with dynamic tag input for required skills
- Redesign the **Offer detail page** with score-sorted candidate table, recommendation filters, and CV submission entry point
- Create a **CV submission form** with an "analysis in progress" polling state
- Redesign the **Analysis detail page** with structured sections (extracted info, score ring, strengths/weaknesses, AI justification)
- Refine the **Conversational assistant** with candidate-context sidebar integration and tool-call transparency badges
- Create a **Candidate comparison** view (side-by-side scores, gaps, agent verdict)
- Add a **DashboardController** for the authenticated landing page (`/dashboard`)
- Polish global layout, navigation, and mobile responsiveness throughout

### Non-goals

- No backend logic changes (models, jobs, agents, migrations, enums remain untouched)
- No authentication flow changes
- No new API routes
- No JS framework migration (Blade + Alpine is the stack)

## Capabilities

### New Capabilities

- `dashboard`: Authenticated landing page with offer metrics, recent activity, and quick actions
- `cv-submission`: Per-offer CV submission with async analysis polling and visual feedback
- `candidate-comparison`: Side-by-side comparison of two candidates for a given offer

### Modified Capabilities

No existing specs are being modified — all backend specs remain unchanged.

## Impact

- **New files**: `DashboardController`, `DashboardView` component, CV submission view + partials, candidate comparison view
- **Modified views**: `offres/index`, `offres/create`, `offres/show`, `analyses/show`, `agent-conversations/show`
- **New Blade components**: `score-ring`, `score-bar`, `recommendation-badge`, `skill-tags-input`, `comparison-table`
- **Routes**: New `/dashboard` route, new `/offres/{offre}/cvs` route, new `/comparaison` route
- **No new migrations, models, jobs, or enums**
