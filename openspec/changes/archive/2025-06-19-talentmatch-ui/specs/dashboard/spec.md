## ADDED Requirements

### Requirement: Dashboard displays offer metrics

The system SHALL display a dashboard at `/dashboard` showing aggregate metrics for the authenticated user's job offers.

#### Scenario: Dashboard shows offer count and candidate stats

- **WHEN** an authenticated user visits `/dashboard`
- **THEN** the system displays the total number of offers, total candidates submitted, total analyses completed, and average matching score across all analyses

#### Scenario: Dashboard shows recent offers list

- **WHEN** an authenticated user visits `/dashboard`
- **THEN** the system displays the 5 most recently updated offers with title, candidate count, and a link to view details

#### Scenario: Dashboard shows empty state when no offers exist

- **WHEN** a user with no offers visits `/dashboard`
- **THEN** the system displays an empty state with a call-to-action to create the first offer

### Requirement: Dashboard navigation

The system SHALL provide navigation links to all major sections from the dashboard.

#### Scenario: Dashboard links navigate to correct sections

- **WHEN** a user clicks "Offres" on the dashboard
- **THEN** the system navigates to `/offres`

#### Scenario: Quick action creates a new offer

- **WHEN** a user clicks "Nouvelle offre" on the dashboard
- **THEN** the system navigates to `/offres/create`
