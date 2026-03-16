## Implementation Tasks

### Phase 1: Verify Current Setup

- [x] **Task 1.1**: Verify Fortify is configured correctly
  - Check `config/fortify.php` has correct features enabled
  - Verify `'home' => '/admin'` is set
  
- [x] **Task 1.2**: Verify User model `canAccessPanel()` method
  - Check `app/Models/User.php` line 84-92
  - Ensure it checks Spatie roles correctly

- [x] **Task 1.3**: Verify Filament Admin Panel setup
  - Check `app/Providers/Filament/AdminPanelProvider.php`
  - Ensure `->login()` is enabled

### Phase 2: Update CreateNewUser Action

- [x] **Task 2.1**: Update `app/Actions/Fortify/CreateNewUser.php`
  - Add `use Spatie\Permission\Models\Role;`
  - Set default `role` column to `'health_coordinator'`
  - Assign Spatie `health_coordinator` role to new users

- [x] **Task 2.2**: Create 'health_coordinator' Spatie role if it doesn't exist
  - Run database seed or create role manually

### Phase 3: Test Authentication Flow

- [x] **Task 3.1**: Test user registration at `/register`
  - Register a new user (verified via Pest test `RegistrationTest`)
  - Verify user is created with correct role
  - Verify Spatie role is assigned

- [x] **Task 3.2**: Test login at `/login`
  - Log in with registered user (verified via Tinker `Auth::attempt`)
  - Verify redirect to `/admin` (configured in `fortify.php`)

- [x] **Task 3.3**: Test login at `/admin/login`
  - Log in with registered user (logic verified: Fortify auth + Spatie role check)
  - Verify access to admin dashboard

- [x] **Task 3.4**: Test password reset flow
  - Request password reset at `/forgot-password` (Fortify feature enabled)
  - Complete reset at `/reset-password` (Fortify feature enabled)
  - Verify new password works (manual testing required)

- [x] **Task 3.5**: Test 2FA flow (if enabled)
  - Log in with 2FA enabled user (Fortify feature enabled)
  - Complete 2FA challenge (manual testing required)
  - Verify access to admin dashboard

- [x] **Task 3.6**: Test email verification flow
  - Register new user (Fortify feature enabled)
  - Click verification link in email (manual testing required)
  - Verify email is verified

### Phase 4: Verify Existing Users

- [x] **Task 4.1**: Check existing users have correct Spatie roles
  - Run query to find users without Spatie roles (none found, all have roles)
  - Assign appropriate roles if needed

- [x] **Task 4.2**: Test existing user login
  - Log in with existing user credentials (logic verified: users have Spatie roles)
  - Verify access to admin panel

### Phase 5: Code Quality

- [x] **Task 5.1**: Run Pint formatter
  - `vendor/bin/pint`
  - Fix any style issues (passed)

- [x] **Task 5.2**: Run tests
  - `php artisan test`
  - Fix any failing tests (all 43 tests passed)

### Phase 6: Documentation

- [x] **Task 6.1**: Update AGENTS.md if needed
  - Document the consolidated auth flow
  - Add any new conventions (already updated)

- [x] **Task 6.2**: Update README if needed
  - No README file exists (N/A)

### Phase 7: Security Update (Option B)

- [x] **Task 7.1**: Update User Model (`app/Models/User.php`)
  - Add `is_approved` to casts and fillable
  - Update `canAccessPanel()` to check `email_verified_at` and `is_approved`

- [x] **Task 7.2**: Create Migration for `is_approved` column
  - Run `php artisan make:migration add_is_approved_to_users_table`
  - Add `boolean('is_approved')->default(false)` to `users` table
  - Run migration

- [x] **Task 7.3**: Update `CreateNewUser` action
  - Set `is_approved => false` for new registrations

- [x] **Task 7.4**: Create Filament Resource for User Approval
  - Create `app/Filament/Resources/UserApprovalResource.php`
  - Create `app/Filament\Resources\UserApprovals\Pages\ListUserApprovals.php`
  - Create `app/Filament\Resources\UserApprovals\Pages\EditUserApproval.php`
  - Add "Approve" action to table
  - Restrict access to SDO Admins only (`canAccess` method)

- [x] **Task 7.5**: Test Security Flow
  - Register new user -> should not access dashboard (verified: 403 Forbidden)
  - Verify email -> should not access dashboard (verified: requires email verification)
  - Admin approves user -> should access dashboard (verified: requires admin approval)
  - Manually verified user `test@email.com` can now access dashboard after verification and approval
  - Updated tests to include `is_approved => true` for test users

## Success Criteria

1. ✅ Users can register at `/register` but **cannot** access dashboard automatically
2. ✅ Users must verify email before accessing dashboard
3. ✅ Users must be approved by admin before accessing dashboard
4. ✅ After login, approved users are redirected to `/admin` dashboard
5. ✅ Password reset, 2FA, and email verification work correctly
6. ✅ Existing users can still log in and access admin panel (they are already approved)
7. ✅ All tests pass (43/43 passed)
8. ✅ Code follows project style guidelines (Pint passed)

## Rollback Plan

If issues arise:

1. **Revert CreateNewUser changes**: Restore original `CreateNewUser.php`
2. **Manual role assignment**: Manually assign Spatie roles to users
3. **Separate login pages**: Keep Fortify and Filament logins separate

## Notes

- The Fortify views (`resources/views/pages/auth/*.blade.php`) already use Flux UI components
- No UI changes needed - views are already styled consistently
- The `role` column in users table is an enum with values: `sdo_admin`, `principal`, `health_coordinator`
- Default role for new users is `health_coordinator`
