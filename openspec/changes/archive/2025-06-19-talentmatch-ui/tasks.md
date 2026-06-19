## 1. Blade Components

- [x] 1.1 Create `<x-score-ring>` component (`resources/views/components/score-ring.blade.php`) — inline SVG donut with stroke-dasharray, accepts `score` (int), size defaults to 48px
- [x] 1.2 Create `<x-score-bar>` component (`resources/views/components/score-bar.blade.php`) — Tailwind progress bar with color thresholds (emerald/amber/red)
- [x] 1.3 Create `<x-recommendation-badge>` component (`resources/views/components/recommendation-badge.blade.php`) — color-coded pill mapped to Recommandation enum values
- [x] 1.4 Create `<x-skill-input>` component (`resources/views/components/skill-input.blade.php`) — Alpine.js tag input with Enter/comma to add, × to remove, hidden JSON field

## 2. Routes & Navigation

- [x] 2.1 Add dashboard route to `routes/web.php` (`GET /dashboard`)
- [x] 2.2 Add CV submission route to `routes/web.php` (`POST /offres/{offre}/soumettre-cv`)
- [x] 2.3 Add analysis status route to `routes/web.php` (`GET /analyses/{analyse}/status`)
- [x] 2.4 Add candidate comparison route to `routes/web.php` (`GET /offres/{offre}/comparer`)
- [x] 2.5 Add dashboard link to `resources/views/layouts/navigation.blade.php`

## 3. Dashboard Landing Page

- [x] 3.1 Create `DashboardController` at `app/Http/Controllers/DashboardController.php` with `index()` — aggregates offer count, candidate count, analyses count, avg score, recent offers
- [x] 3.2 Create dashboard view at `resources/views/dashboard/index.blade.php` — metrics grid, recent offers table, empty state, "Nouvelle offre" CTA

## 4. Offers List Redesign

- [x] 4.1 Update `OffreController@index` to eager-load analyses count and latest analysis
- [x] 4.2 Redesign `resources/views/offres/index.blade.php` — card-based grid layout, candidate count per offer, last activity date, "Nouvelle offre" button, empty state

## 5. Offer Create Form Redesign

- [x] 5.1 Redesign `resources/views/offres/create.blade.php` — integrate `<x-skill-input>` for competences_requises, experience_min select, description textarea, validation states

## 6. Offer Detail + CV Submission

- [x] 6.1 Update `OffreController@show` to eager-load analyses with candidat, sorted by matching_score desc
- [x] 6.2 Redesign `resources/views/offres/show.blade.php` — offer criteria at top, candidate table with score bars, recommendation badges, "Soumettre un CV" button
- [x] 6.3 Add `OffreController@submitCv` method — validates input, creates Candidat, dispatches AnalyseCvJob, returns analyse ID
- [x] 6.4 Add `AnalyseController@status` method — returns JSON with analyse status
- [x] 6.5 Create CV submission partial view `resources/views/offres/partials/soumettre-cv.blade.php` — form with Alpine.js polling, spinner, error state

## 7. Analysis Detail Redesign

- [x] 7.1 Update `AnalyseController@show` to eager-load offre, candidat
- [x] 7.2 Redesign `resources/views/analyses/show.blade.php` — score ring, recommendation badge, extracted info section, strengths/weaknesses/missing-skills columns, justification text, "Poser une question" button

## 8. Assistant Integration on Analysis Detail

- [x] 8.1 Create chat modal partial at `resources/views/analyses/partials/chat-modal.blade.php` — message list, input form, Alpine.js for modal toggle
- [x] 8.2 Wire the "Poser une question" button to open the modal with candidate context pre-filled

## 9. Candidate Comparison

- [x] 9.1 Add `OffreController@comparer` method — validates 2 candidate IDs, fetches both analyses
- [x] 9.2 Create comparison view at `resources/views/offres/comparer.blade.php` — two-column grid with score rings, info sections, strengths/weaknesses, AI verdict placeholder

## 10. Polish & Testing

- [x] 10.1 Verify mobile responsiveness on all views (test breakpoints sm/md/lg)
- [x] 10.2 Verify accessibility (labels on all inputs, focus-visible, color contrast for score badges)
- [x] 10.3 Run `vendor/bin/pint --format agent` on all modified PHP files
- [x] 10.4 Run `php artisan test --compact` to verify no backend breakage
