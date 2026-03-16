## ADDED Requirements

### Requirement: Dashboard displays user-specific overview statistics
The system SHALL display aggregated statistics relevant to the authenticated user's role and school on the dashboard landing page.

#### Scenario: Nurse views dashboard statistics
- **WHEN** a nurse (health_coordinator) logs in and navigates to /dashboard
- **THEN** system displays health-related statistics (health records count, vaccinations count, etc.)

#### Scenario: Principal views dashboard statistics
- **WHEN** a principal logs in and navigates to /dashboard
- **THEN** system displays school-specific statistics (student count, health records for their school)

#### Scenario: SDO Admin views dashboard statistics
- **WHEN** an SDO admin logs in and navigates to /dashboard
- **THEN** system displays comprehensive statistics across all schools

### Requirement: Dashboard shows recent activity
The system SHALL display recent health records on the dashboard for quick access.

#### Scenario: View recent health records
- **WHEN** user navigates to /dashboard
- **THEN** system displays up to 5 recent health records with student name, date, and BMI category

#### Scenario: No recent records available
- **WHEN** user navigates to /dashboard and has no health records
- **THEN** system displays "No records found" message

### Requirement: Dashboard provides school context
The system SHALL display the user's school name or "SDO Wide" for SDO admins on the dashboard.

#### Scenario: Nurse sees their school name
- **WHEN** a nurse navigates to /dashboard
- **THEN** system displays their assigned school name as subheading

#### Scenario: SDO Admin sees "SDO Wide"
- **WHEN** an SDO admin navigates to /dashboard
- **THEN** system displays "SDO Wide" as subheading
