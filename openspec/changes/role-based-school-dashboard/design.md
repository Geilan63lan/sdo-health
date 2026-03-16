## Architecture

### User Model
The `User` model has a `school_id` relationship. Coordinators are assigned to a specific school.

### Data Scoping Strategy
We will use Laravel's global scopes or Filament resource queries to filter data based on the user's role and school assignment.

#### 1. Global Scope (Optional)
We can add a global scope to models like `Student`, `HealthRecord`, etc., to automatically filter queries for coordinators.
```php
// In Student model
protected static function booted()
{
    static::addGlobalScope('school', function (Builder $builder) {
        if (auth()->user()->hasRole('health_coordinator')) {
            $builder->where('school_id', auth()->user()->school_id);
        }
    });
}
```
*Note: Global scopes can be complex if admins need to see all data. We might prefer local scopes or Filament resource queries.*

#### 2. Filament Resource Queries (Preferred)
We will modify the `table()` method in each Filament Resource to apply the school filter for coordinators.

Example for `StudentResource`:
```php
public static function table(Table $table): Table
{
    return $table
        ->query(function (Builder $query) {
            if (auth()->user()->hasRole('health_coordinator')) {
                $query->where('school_id', auth()->user()->school_id);
            }
        })
        ->columns([
            // ...
        ]);
}
```

#### 3. Form Scoping
When creating/editing students, coordinators should not be able to select a school different from their own.
- The `school_id` field in the student form should be hidden and auto-filled for coordinators.
- Admins should see the full `school_id` dropdown.

### Dashboard Organization
- **Admin Dashboard**: Shows summary stats for all schools.
- **Coordinator Dashboard**: Shows summary stats only for their assigned school.

### Security Checks
- `canAccessPanel` already checks for roles.
- We need to ensure data queries are scoped correctly.
- We should also check if the user has permission to view/edit a specific record (e.g., via Policies).

## Implementation Details

### 1. Student Resource
- Update `table()` query to filter by `school_id` for coordinators.
- Update `form()` to hide `school_id` field for coordinators (auto-fill).
- Update `canAccess()` if needed (already restricted by role).

### 2. Health Record Resource
- Update `table()` query to filter by `school_id` (via student relationship).
- Update `form()` to restrict school selection.

### 3. Vaccination Resource
- Update `table()` query to filter by `school_id` (via student relationship).
- Update `form()` to restrict school selection.

### 4. Dashboard Widgets
- Create separate dashboard views for admin and coordinator.
- Or use conditional logic in existing dashboard to show different stats.

### 5. Policies (Optional but Recommended)
- Create policies for each resource to check if the user can view/edit based on school assignment.
- Example: `StudentPolicy@view` checks if the student belongs to the user's school.

## UI/UX Considerations
- Coordinators should see their school name prominently in the dashboard.
- Forms should clearly indicate that the student is being added to the coordinator's school.