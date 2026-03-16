## Context

**Current State:**
- `/dashboard` route renders a Livewire component with admin panel links
- Users (nurses, health coordinators, principals) see the same dashboard with links to `/admin/...`
- Dashboard has redundant functionality - it's essentially a landing page for the admin panel
- Users must click "Go to Admin Panel" to access actual functionality

**Constraints:**
- Must maintain `/dashboard` as the user landing page URL
- Must preserve existing authentication and authorization
- Must work with Spatie role-based permissions
- Must not break existing admin panel functionality

**Stakeholders:**
- Nurses/Health Coordinators: Need quick access to health records, vaccinations
- Principals: Need access to student and health records for their school
- SDO Admin: Full access to all admin functions

## Goals / Non-Goals

**Goals:**
- Create a focused, simplified dashboard landing page for users
- Remove redundant admin panel links from dashboard
- Add clear "Login" button as the primary entry to admin panel
- Provide user-specific overview statistics
- Maintain separate `/dashboard` and `/admin` URL structure

**Non-Goals:**
- Rebuilding the entire admin panel interface
- Adding complex new functionality to the dashboard
- Changing authentication mechanisms
- Modifying Filament admin panel structure

## Decisions

### Decision 1: Dashboard Structure
**Choice:** Keep `/dashboard` as a simple landing page with:
- User welcome message and school info
- Key statistics (student count, health records, etc.)
- "Login to Admin Panel" button as primary action
- No direct links to admin resources

**Rationale:** Avoids redundancy while providing clear user guidance. The dashboard becomes a true landing page, not a duplicate of the admin interface.

**Alternatives Considered:**
- Remove `/dashboard` entirely → Would break user expectations and existing navigation
- Keep all admin links → Maintains current redundancy problem

### Decision 2: Login Button Placement
**Choice:** Prominent "Login to Admin Panel" button in the dashboard header

**Rationale:** Provides clear call-to-action for users to access the full admin functionality. This serves as the "door" to the admin panel.

**Alternatives Considered:**
- Auto-redirect after login → Would hide the dashboard's purpose
- Multiple login options → Overcomplicates the interface

### Decision 3: User-Specific Data Display
**Choice:** Dashboard shows aggregated statistics relevant to user's role and school

**Rationale:** Provides immediate value without requiring navigation to admin panel. Nurses see health stats, principals see school stats.

**Alternatives Considered:**
- Show all data → Information overload for dashboard
- Show no data → Dashboard becomes just a login page

### Decision 4: Navigation Structure
**Choice:** Minimal navigation on dashboard - only essential links

**Rationale:** Keeps dashboard focused on its purpose as a landing page. Users navigate to admin panel for full functionality.

**Alternatives Considered:**
- Full navigation menu → Blurs line between dashboard and admin panel

## Risks / Trade-offs

**Risk:** Users might be confused about dashboard vs admin panel distinction
- **Mitigation:** Clear labeling and messaging about dashboard purpose

**Risk:** Removing admin links might frustrate power users
- **Mitigation:** Admin panel is still easily accessible via prominent login button

**Risk:** Dashboard might become too simplistic
- **Mitigation:** Keep essential overview statistics and quick actions

**Risk:** Breaking existing user workflows
- **Mitigation:** Test with existing user roles before deployment

## Migration Plan

1. **Phase 1: Update Dashboard View**
   - Remove admin panel links from dashboard template
   - Add prominent login button
   - Restructure layout for better spacing

2. **Phase 2: Update Dashboard Component**
   - Focus on user-specific data only
   - Remove references to admin panel navigation

3. **Phase 3: Testing**
   - Test with nurse role
   - Test with health coordinator role
   - Test with principal role
   - Test with SDO admin role

4. **Phase 4: Deployment**
   - Deploy to staging environment
   - User acceptance testing
   - Deploy to production

5. **Rollback Strategy**
   - Keep previous dashboard version in git history
   - Can revert dashboard view and component if issues arise

## Open Questions

1. Should the dashboard show "Recent Activity" section?
2. Should we add a "Quick Guide" or help section to the dashboard?
3. Should dashboard statistics update in real-time or on page load only?
