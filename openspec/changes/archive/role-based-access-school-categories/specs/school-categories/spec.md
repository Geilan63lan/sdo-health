## ADDED Requirements

### Requirement: Schools have category field
The system SHALL store a category for each school (elementary, junior_high, senior_high).

#### Scenario: Create school with category
- **WHEN** an SDO Admin creates a new school
- **THEN** system requires selecting a category from the available options

#### Scenario: Update school category
- **WHEN** an SDO Admin updates an existing school
- **THEN** system allows changing the school category

### Requirement: Schools can be filtered by category
The system SHALL allow filtering schools by their category.

#### Scenario: Filter schools by elementary
- **WHEN** user filters schools by "elementary" category
- **THEN** system displays only elementary schools

#### Scenario: Filter schools by junior high
- **WHEN** user filters schools by "junior_high" category
- **THEN** system displays only junior high schools

#### Scenario: Filter schools by senior high
- **WHEN** user filters schools by "senior_high" category
- **THEN** system displays only senior high schools
