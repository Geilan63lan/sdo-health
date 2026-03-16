## Why

The current `/dashboard` route is redundant and adds unnecessary complexity. Users need a clear, role-based access system where:
- SDO Admins get full access to all features
- Health Coordinators and Principals are restricted to their assigned school only
- Schools can be categorized (Elementary, Junior High, Senior High) for better filtering and management

## What Changes

- **BREAKING**: Remove `/dashboard` route entirely
- **BREAKING**: Redirect all users to `/admin` after authentication
- **New**: Add school category field to schools (Elementary, Junior High, Senior High)
- **Modified**: Update Filament resource policies to restrict access based on user role and school assignment
- **Modified**: Update sidebar navigation to show only relevant resources based on user role

## Capabilities

### New Capabilities
- `school-categories`: Add category field to schools (Elementary, Junior High, Senior High) with filtering
- `role-based-filament-access`: Implement Filament resource policies for role-based access control

### Modified Capabilities
- `user-authentication`: Remove dashboard redirect, redirect to admin panel instead
- `school-management`: Add category filtering to school resources
- `health-record-access`: Restrict access based on user school assignment

## Impact

- **Code Changes**:
  - Remove `/dashboard` route from `routes/web.php`
  - Update authentication redirect in `AppServiceProvider`
  - Add `category` field to `schools` table migration
  - Update School model with category enum
  - Create Filament policies for all resources
  - Update sidebar navigation logic

- **User Experience**:
  - Users no longer see `/dashboard` - they go directly to admin panel
  - Health Coordinators/Principals see only their school's data
  - SDO Admins see all data across all schools
  - Schools can be filtered by category (Elementary, Junior High, Senior High)

- **Breaking Changes**:
  - `/dashboard` route removed
  - Users redirected to `/admin` after login
  - Health Coordinators/Principals cannot see other schools' data
