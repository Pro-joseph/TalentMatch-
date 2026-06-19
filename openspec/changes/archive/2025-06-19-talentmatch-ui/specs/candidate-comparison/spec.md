## ADDED Requirements

### Requirement: User can compare two candidates for an offer

The system SHALL allow users to select two candidates from an offer's candidate list and view them side by side.

#### Scenario: Comparison link is available from offer detail

- **WHEN** a user views an offer detail page with at least 2 analysed candidates
- **THEN** the system displays checkboxes next to each candidate row and a "Comparer" button when exactly 2 are selected

#### Scenario: Comparison page shows side-by-side layout

- **WHEN** a user navigates to `/offres/{offre}/comparer?candidats[]=id1&candidats[]=id2`
- **THEN** the system displays both candidates' analyses in a two-column grid with score rings, extracted info, strengths, weaknesses, and missing skills

### Requirement: Comparison shows AI-generated verdict

The system SHALL generate a comparative verdict using the HrAssistant agent.

#### Scenario: Comparison verdict is generated on page load

- **WHEN** a user opens the comparison page
- **THEN** the system calls the HrAssistant agent to generate a verdict comparing both candidates, displayed below the two columns with a loading state while generating

### Requirement: Score and recommendation are visually prominent

The system SHALL display each candidate's matching score as an SVG ring and their recommendation as a color-coded badge.

#### Scenario: Score ring uses correct color thresholds

- **WHEN** a candidate has matching_score ≥ 70
- **THEN** the score ring uses green stroke
- **WHEN** a candidate has matching_score 40–69
- **THEN** the score ring uses amber stroke
- **WHEN** a candidate has matching_score < 40
- **THEN** the score ring uses red stroke
