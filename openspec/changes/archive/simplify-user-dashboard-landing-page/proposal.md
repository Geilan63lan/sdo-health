## Why

The current `/dashboard` page is redundant - it simply links to the `/admin` panel without providing any unique value. Users (especially nurses and health coordinators) need a simplified, focused landing page that doesn't require navigating through the full admin interface. This will improve user experience by providing a clear entry point with essential actions and overview statistics.

## What Changes

- **Remove redundant dashboard links**: Dashboard will no longer link to every admin resource
- **Create dedicated dashboard sections**: Dashboard will have its own focused views for common tasks
- **Add login button as primary entry**: Clear "Login" button as the door to the admin panel
- **Simplify navigation**: Dashboard will have minimal navigation, focusing on user-specific content
- **Maintain separate URL structure**: `/dashboard` remains as the user landing page, `/admin` for full admin access

## Capabilities

### New Capabilities
- `user-dashboard-overview`: Dashboard landing page with user-specific stats and quick actions
- `dashboard-auth-gateway`: Login button and authentication flow from dashboard to admin panel

### Modified Capabilities
- `user-dashboard`: Current dashboard implementation will be modified to remove admin panel links and add focused user content

## Impact

- **Code Changes**:
  - `resources/views/livewire/dashboard.blade.php`: Remove admin panel links, add login button, restructure layout
  - `app/Livewire/Dashboard.php`: Update component to focus on user-specific data
  - `routes/web.php`: May need adjustment for dashboard routing
  - `app/Providers/Filament/AdminPanelProvider.php`: Ensure proper auth flow

- **User Experience**:
  - Nurses/Health Coordinators will see a simplified dashboard
  - Clear path to login and access admin panel
  - No confusion about which interface to use

- **Breaking Changes**:
  - Dashboard links to `/admin` will be removed
  - Users will need to use the login button to access admin panel
