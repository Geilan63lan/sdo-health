## MODIFIED Requirements

### Requirement: User redirects to dashboard after login
The system SHALL redirect authenticated users to the dashboard page.

#### Scenario: Nurse redirects to dashboard
- **WHEN** a nurse (health_coordinator) logs in
- **THEN** system redirects to /dashboard

#### Scenario: Principal redirects to dashboard
- **WHEN** a principal logs in
- **THEN** system redirects to /dashboard

## REMOVED Requirements

### Requirement: Dashboard route exists
**Reason**: Dashboard is redundant and not needed for role-based access
**Migration**: Users will be redirected to /admin instead

### Requirement: Dashboard page displays overview
**Reason**: Dashboard functionality is redundant with admin panel
**Migration**: Overview stats will be available in admin panel dashboard

## ADDED Requirements

### Requirement: Users redirect to admin panel after login
The system SHALL redirect authenticated users to the admin panel instead of dashboard.

#### Scenario: User redirects to admin panel
- **WHEN** any authenticated user logs in
- **THEN** system redirects to /admin

#### Scenario: Admin panel is entry point
- **WHEN** user navigates to application root
- **THEN** system shows login page, then redirects to /admin after authentication
