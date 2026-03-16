## ADDED Requirements

### Requirement: Dashboard has prominent login button
The system SHALL display a clear "Login to Admin Panel" button on the dashboard landing page.

#### Scenario: Login button is visible
- **WHEN** user navigates to /dashboard
- **THEN** system displays a prominent login button in the header section

#### Scenario: Login button styling
- **WHEN** login button is displayed
- **THEN** system uses primary button styling with cog icon

### Requirement: Login button navigates to admin panel
The system SHALL redirect users to the admin panel when they click the login button.

#### Scenario: Click login button
- **WHEN** user clicks "Login to Admin Panel" button
- **THEN** system navigates to /admin (Filament admin panel)

#### Scenario: Login button uses correct route
- **WHEN** login button is rendered
- **THEN** system uses route('filament.admin.pages.dashboard') for navigation

### Requirement: Dashboard serves as authentication gateway
The system SHALL use dashboard as the primary entry point for users before accessing admin panel.

#### Scenario: User lands on dashboard after authentication
- **WHEN** user logs in successfully
- **THEN** system redirects to /dashboard (not directly to /admin)

#### Scenario: Dashboard provides clear path to admin
- **WHEN** user is on dashboard
- **THEN** system provides clear visual indication of how to access admin panel
