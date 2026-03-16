## ADDED Requirements

### Requirement: School form includes category field
The system SHALL display category field in school create/edit forms.

#### Scenario: Create school form shows category
- **WHEN** user opens create school form
- **THEN** system displays category dropdown with options

#### Scenario: Edit school form shows category
- **WHEN** user opens edit school form
- **THEN** system displays category dropdown with current value selected

### Requirement: School table shows category column
The system SHALL display category column in school list table.

#### Scenario: School list shows category
- **WHEN** user views schools list
- **THEN** system displays category column for each school

### Requirement: School filter by category
The system SHALL provide filter for schools by category.

#### Scenario: Filter schools by category
- **WHEN** user applies category filter
- **THEN** system filters schools to show only selected category
