## ADDED Requirements

### Requirement: User can submit a CV for a specific offer

The system SHALL allow authenticated users to submit a candidate CV from the offer detail page. The form SHALL collect the candidate name and CV text.

#### Scenario: CV submission form is accessible from offer detail

- **WHEN** a user views an offer detail page
- **THEN** the system displays a "Soumettre un CV" button that opens the submission form

#### Scenario: Successful CV submission creates analysis job

- **WHEN** a user fills in candidate name and CV text, then submits
- **THEN** the system creates a new Candidat record, dispatches an AnalyseCvJob, and returns the analyse ID

### Requirement: CV submission shows analysis progress

The system SHALL provide visual feedback during async CV analysis. After submission, the system SHALL poll for analysis status and display a loading state until analysis completes.

#### Scenario: User sees "analyse en cours" after submission

- **WHEN** a user submits a CV
- **THEN** the system shows a spinner with "Analyse en cours..." and polls `/analyses/{id}/status` every 2 seconds

#### Scenario: User is redirected when analysis completes

- **WHEN** the analysis status changes to `completed` during polling
- **THEN** the system navigates to the analysis detail page (`/analyses/{id}`)

#### Scenario: User sees error if analysis fails

- **WHEN** the analysis status is `failed`
- **THEN** the system displays an error message and allows the user to retry

### Requirement: Form validates required fields

The system SHALL validate that candidate name and CV text are provided before submission.

#### Scenario: Form shows validation errors

- **WHEN** a user submits the form with empty name or empty CV text
- **THEN** the system displays inline validation errors and does not submit
