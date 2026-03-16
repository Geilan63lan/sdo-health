## 1. Database Changes

- [x] 1.1 Create migration to add category field to schools table
- [x] 1.2 Create enum class for school categories (elementary, junior_high, senior_high)
- [x] 1.3 Update School model with category field and enum casting
- [x] 1.4 Backfill existing schools with appropriate categories

## 2. Remove Dashboard Route

- [x] 2.1 Remove `/dashboard` route from `routes/web.php`
- [x] 2.2 Update authentication redirect to `/admin` in Fortify config
- [x] 2.3 Remove dashboard view file (`resources/views/dashboard.blade.php`)
- [x] 2.4 Remove dashboard Livewire component (`app/Livewire/Dashboard.php`)

## 3. Create Filament Policies

- [x] 3.1 Create base policy for School resource
- [x] 3.2 Create base policy for HealthRecord resource
- [x] 3.3 Create base policy for Vaccination resource
- [x] 3.4 Create base policy for Absence resource
- [x] 3.5 Create base policy for Student resource
- [x] 3.6 Create base policy for HealthProgram resource

## 4. Implement Policy Logic

- [x] 4.1 Implement SDO Admin full access in policies
- [x] 4.2 Implement Health Coordinator school restriction in policies
- [x] 4.3 Implement Principal school restriction in policies
- [x] 4.4 Test policy access control

## 5. Update Filament Resources

- [x] 5.1 Update School resource to add category field
- [x] 5.2 Update School resource to add category filter
- [x] 5.3 Apply policies to all Filament resources
- [x] 5.4 Test resource filtering by user role

## 6. Testing

- [x] 6.1 Test SDO Admin can access all data
- [x] 6.2 Test Health Coordinator can only access their school
- [x] 6.3 Test Principal can only access their school
- [x] 6.4 Test school category filtering works
- [x] 6.5 Test users cannot bypass restrictions

## 7. Documentation

- [x] 7.1 Update user documentation about access control
- [x] 7.2 Add comments in policies explaining logic
- [x] 7.3 Verify all specs are implemented
