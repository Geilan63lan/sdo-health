## Why

Currently, the dashboard does not distinguish between different user roles regarding data visibility. An admin sees all data, but a coordinator should only see data relevant to their assigned school. This is a privacy and data security requirement.

Without this restriction, a coordinator from one school could potentially view sensitive health data (students, records) from another school, which violates privacy principles.

## What Changes

- **Admin View**: Unchanged. Admins can see all schools, all students, and all records.
- **Coordinator View**: Restricted. Coordinators can only see:
  - Students enrolled in their assigned school.
  - Health records and vaccinations for those students.
  - Their own school's data.
- **Creation Permissions**: Coordinators can only add students to their assigned school. They cannot select a different school when creating a student.
- **Data Isolation**: All queries for coordinator views will be scoped to their `school_id`.

## Capabilities

### New Capabilities
- `school-scoped-data`: Data queries are automatically scoped to the user's assigned school.
- `role-based-dashboard`: Dashboard content changes based on user role (Admin vs Coordinator).

### Modified Capabilities
- `student-management`: Student list and creation forms are scoped to the coordinator's school.
- `health-record-view`: Health records are filtered by the coordinator's school.

## Impact

### Code Changes
- **Models**: `Student`, `HealthRecord`, `Vaccination`, etc. need to be scoped.
- **Filament Resources**: Update resource queries to filter by `school_id` for coordinators.
- **Forms**: Student creation form should default or restrict `school_id` to the coordinator's school.
- **Dashboard**: Update dashboard widgets to show school-specific stats for coordinators.

### User Experience
- Coordinators see a focused view of their school's data.
- Improved privacy and data security.
- Admins retain full visibility.

### Breaking Changes
- None. Existing functionality for admins is preserved.