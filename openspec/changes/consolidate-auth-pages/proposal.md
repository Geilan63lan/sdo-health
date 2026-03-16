## Why

Currently, the application has two separate authentication systems:
1. **Fortify** (`/login`, `/register`) - General authentication with password reset, 2FA, email verification
2. **Filament** (`/admin/login`) - Admin panel authentication with role-based access

This creates a confusing user experience where users don't know which login page to use. Additionally, new users who register via Fortify may not have the correct Spatie roles assigned, preventing them from accessing the admin panel.

The goal is to consolidate these into a single, unified authentication experience while maintaining all necessary security features (password reset, 2FA, email verification).

## What Changes

- **Unified Login Flow**: Users can log in via either `/login` or `/admin/login` and reach the same dashboard
- **Fortify as Primary Auth**: Fortify handles all authentication (login, register, password reset, 2FA, email verification)
- **Filament as Admin Interface**: Filament provides the admin dashboard and resources, using Fortify's authentication
- **Automatic Role Assignment**: New users automatically get the `health_coordinator` Spatie role during registration
- **Redirect After Login**: All users are redirected to `/admin` dashboard after successful login
- **Consistent UI**: Fortify views already use Flux UI components, matching Filament's style

## Capabilities

### New Capabilities
- `unified-auth-flow`: Single authentication system for both Fortify and Filament
- `role-assignment-on-register`: Automatic Spatie role assignment during user registration

### Modified Capabilities
- `user-registration`: Updated `CreateNewUser` action to assign Spatie roles
- `admin-access-control`: Filament's `canAccessPanel()` now works correctly with assigned roles

## Impact

### Code Changes
- **`app/Actions/Fortify/CreateNewUser.php`**: Assign `health_coordinator` Spatie role during registration
- **`config/fortify.php`**: Already configured with `'home' => '/admin'` (redirect after login)
- **`app/Models/User.php`**: `canAccessPanel()` method checks Spatie roles (already correct)
- **`resources/views/pages/auth/*.blade.php`**: Fortify views already use Flux UI (no changes needed)

### User Experience
- Users see a single, consistent login page (Fortify)
- Registration automatically grants access to admin panel
- Password reset, 2FA, and email verification work seamlessly
- Clear path from login to admin dashboard

### Breaking Changes
- None - existing functionality is preserved, just consolidated

## Non-Goals
- Remove Fortify features (password reset, 2FA, email verification)
- Change the admin panel interface or resources
- Modify user roles or permissions structure
