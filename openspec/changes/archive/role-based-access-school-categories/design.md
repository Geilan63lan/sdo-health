## Context

**Current State:**
- `/dashboard` route exists but is redundant
- Users have role-based access but no school-based data filtering in Filament
- Schools don't have categories (Elementary, Junior High, Senior High)
- All users can potentially see all data depending on implementation

**Constraints:**
- Must maintain existing authentication (Fortify)
- Must work with Spatie role-based permissions
- Must not break existing admin panel functionality
- Must support gradual migration of existing data

**Stakeholders:**
- SDO Admins: Need full access to all schools and data
- Health Coordinators: Need access only to their assigned school
- Principals: Need access only to their assigned school
- Developers: Need clear implementation path

## Goals / Non-Goals

**Goals:**
- Remove `/dashboard` route and redirect to `/admin`
- Implement school categories (Elementary, Junior High, Senior High)
- Create Filament policies for role-based access control
- Filter data based on user's school assignment
- Add school category filtering to school management

**Non-Goals:**
- Rebuilding the entire Filament admin panel
- Changing authentication mechanism
- Adding complex new features beyond access control
- Modifying existing data structures beyond school categories

## Decisions

### Decision 1: Remove Dashboard Route
**Choice:** Completely remove `/dashboard` route and redirect all users to `/admin` after login

**Rationale:** Dashboard was redundant - it just linked to admin panel. Users should go directly to the admin interface.

**Alternatives Considered:**
- Keep dashboard as landing page → Maintains redundancy
- Create separate landing page → More complexity

### Decision 2: School Categories
**Choice:** Add `category` field to schools table with enum: `elementary`, `junior_high`, `senior_high`

**Rationale:** Allows filtering and organizing schools by education level

**Alternatives Considered:**
- Add as free text field → Less structured, harder to filter
- Use existing classification → No existing classification field

### Decision 3: Filament Policies
**Choice:** Create Laravel policies for each Filament resource to control access based on user role and school

**Rationale:** Filament integrates natively with Laravel policies for authorization

**Alternatives Considered:**
- Use Filament custom page authorization → More complex
- Manual filtering in resource classes → Less maintainable

### Decision 4: Data Filtering Strategy
**Choice:** Use model scopes and Filament query builders to filter data based on user's school

**Rationale:** Centralizes filtering logic in one place per resource

**Alternatives Considered:**
- Filter in each page/component → Duplicates logic
- Global scopes → Too restrictive for SDO Admins

### Decision 5: Migration Approach
**Choice:** Create migration to add category field, then backfill existing schools

**Rationale:** Gradual migration without breaking existing data

**Alternatives Considered:**
- Require category on create → Breaks existing schools
- Default value → Might not be accurate

## Risks / Trade-offs

**Risk:** Users might be confused by missing dashboard
- **Mitigation:** Clear messaging about direct admin panel access

**Risk:** Filament policies might be complex to implement
- **Mitigation:** Start with simple policies, iterate based on testing

**Risk:** School categories might not match all schools
- **Mitigation:** Allow "Other" category or custom categories in future

**Risk:** Breaking existing user workflows
- **Mitigation:** Test with all user roles before deployment

## Migration Plan

1. **Phase 1: Database Changes**
   - Add category field to schools table
   - Backfill existing schools with appropriate categories

2. **Phase 2: Remove Dashboard**
   - Remove `/dashboard` route
   - Update authentication redirect to `/admin`

3. **Phase 3: Create Policies**
   - Create base policy for each resource
   - Implement role-based and school-based filtering

4. **Phase 4: Update Filament Resources**
   - Apply policies to all resources
   - Add school category filtering

5. **Phase 5: Testing**
   - Test with SDO Admin role
   - Test with Health Coordinator role
   - Test with Principal role
   - Test school category filtering

6. **Phase 6: Deployment**
   - Deploy to staging
   - User acceptance testing
   - Deploy to production

7. **Rollback Strategy**
   - Keep previous dashboard route in git history
   - Can revert route removal if issues arise
   - Database migration can be rolled back

## Open Questions

1. Should we add "Other" category for schools that don't fit the three categories?
2. Should school categories be configurable by SDO Admin?
3. How should we handle schools with multiple education levels (K-12)?
4. Should we add a "All Schools" filter for SDO Admins?
