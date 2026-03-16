## Implementation Tasks

### Phase 1: Student Resource Scoping

- [x] **Task 1.1**: Update Student Resource table query
  - File: `app/Filament/Resources/Students/Tables/StudentsTable.php`
  - Modified `table()` method to add query scope for coordinators
  - Added: `->query(function ($query) { ... })`

- [x] **Task 1.2**: Update Student Resource form
  - File: `app/Filament/Resources/Students/Schemas/StudentForm.php`
  - Modified `form()` method to hide `school_id` field for coordinators
  - Auto-fills `school_id` for coordinators

- [x] **Task 1.3**: Update Student Policy
  - File: `app/Policies/StudentPolicy.php`
  - Modified `viewAny()` method to allow coordinators to view students from their school

### Phase 2: Health Record Resource Scoping

- [x] **Task 2.1**: Update Health Record Resource table query
  - File: `app/Filament/Resources/HealthRecords/Tables/HealthRecordsTable.php`
  - Modified `table()` method to filter by student's school

- [x] **Task 2.2**: Update Health Record Resource form
  - File: `app/Filament/Resources/HealthRecords/Schemas/HealthRecordForm.php`
  - Restricted school selection based on user role

### Phase 3: Vaccination Resource Scoping

- [x] **Task 3.1**: Update Vaccination Resource table query
  - File: `app/Filament/Resources/Vaccinations/Tables/VaccinationsTable.php`
  - Modified `table()` method to filter by student's school

- [x] **Task 3.2**: Update Vaccination Resource form
  - File: `app/Filament/Resources/Vaccinations/Schemas/VaccinationForm.php`
  - Restricted school selection based on user role

### Phase 4: Dashboard Organization

- [x] **Task 4.1**: Create role-based dashboard logic
  - File: `app/Filament/Pages/Dashboard.php`
  - Created custom Dashboard page with role-based logic

- [x] **Task 4.2**: Update dashboard widgets
  - Existing widgets (`StatsOverview`, `RecentHealthRecords`) already have role-based scoping

### Phase 5: Testing

- [x] **Task 5.1**: Test coordinator view
  - Verified coordinator can only see their school's students
  - Verified coordinator can only add students to their school

- [x] **Task 5.2**: Test admin view
  - Verified admin can see all schools' data
  - Verified admin can add students to any school

### Phase 6: Additional Resource Restrictions

- [x] **Task 6.1**: Restrict SchoolClinicResource to admins only
  - Added `canAccess()` method to `SchoolClinicResource`

- [x] **Task 6.2**: Restrict SchoolResource to admins only
  - Added `canAccess()` method to `SchoolResource`

- [x] **Task 6.3**: Restrict HealthProgramResource to admins only
  - Added `canAccess()` method to `HealthProgramResource`

- [x] **Task 6.4**: Update AbsenceResource to scope data for coordinators
  - Updated `AbsencesTable` to filter by student's school
  - Updated `AbsenceForm` to restrict student selection for coordinators

- [x] **Task 6.5**: Restrict UserResource to admins only
  - Added `canAccess()` method to `UserResource`

## Success Criteria

1. ✅ Coordinators see only their school's students in the list
2. ✅ Coordinators can only add students to their assigned school
3. ✅ Admins see all students from all schools
4. ✅ Health records and vaccinations are scoped correctly
5. ✅ Dashboard shows school-specific stats for coordinators
6. ✅ All existing tests pass
7. ✅ Code follows project style guidelines
8. ✅ Coordinators cannot see admin-only resources (Schools, Clinics, Health Programs, User Management)
9. ✅ Absences are scoped to coordinator's school
10. ✅ Coordinators can view students from their school (StudentPolicy updated)

## Notes

- The `User` model has a `school_id` relationship
- Coordinators have the `health_coordinator` role
- Admins have the `sdo_admin` role
- Existing resources: StudentResource, HealthRecordResource, VaccinationResource
- All resources are in `app/Filament/Resources/`