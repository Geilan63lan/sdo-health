<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create permissions
    $this->permissions = collect([
        'view_admin_panel',
        'manage_schools',
        'manage_students',
        'manage_health_records',
        'manage_vaccinations',
        'manage_absences',
        'manage_health_programs',
    ])->map(fn ($name) => Permission::create(['name' => $name]));

    // Create roles
    $this->sdoAdminRole = Role::create(['name' => 'sdo_admin']);
    $this->healthCoordinatorRole = Role::create(['name' => 'health_coordinator']);
    $this->principalRole = Role::create(['name' => 'principal']);

    // Assign permissions to roles
    $this->sdoAdminRole->givePermissionTo($this->permissions->pluck('name')->toArray());
    $this->healthCoordinatorRole->givePermissionTo([
        'view_admin_panel',
        'manage_students',
        'manage_health_records',
        'manage_vaccinations',
        'manage_absences',
        'manage_health_programs',
    ]);
    $this->principalRole->givePermissionTo([
        'view_admin_panel',
        'manage_students',
        'manage_health_records',
    ]);
});

test('health coordinator user has correct role-based permissions', function () {
    $user = User::factory()->create([
        'role' => 'health_coordinator',
        'is_approved' => true,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('health_coordinator');

    expect($user->hasPermissionTo('view_admin_panel'))->toBeTrue();
    expect($user->hasPermissionTo('manage_students'))->toBeTrue();
    expect($user->hasPermissionTo('manage_health_records'))->toBeTrue();
    expect($user->hasPermissionTo('manage_vaccinations'))->toBeTrue();
    expect($user->hasPermissionTo('manage_absences'))->toBeTrue();
    expect($user->hasPermissionTo('manage_health_programs'))->toBeTrue();
    expect($user->hasPermissionTo('manage_schools'))->toBeFalse();
});

test('saving permanent permission stores override with null expires_at', function () {
    $user = User::factory()->create([
        'role' => 'principal',
        'is_approved' => true,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('principal');

    // Simulate what EditUser::beforeSave() does: grant a permanent override
    $permission = Permission::where('name', 'manage_schools')->first();
    $user->permissions()->attach($permission->id, ['expires_at' => null]);
    $user->unsetRelation('permissions');

    // Verify the permission exists in the database
    expect(DB::table('model_has_permissions')
        ->where('model_id', $user->id)
        ->where('permission_id', $permission->id)
        ->exists())->toBeTrue();

    // Verify expires_at is null for permanent grant
    $pivot = DB::table('model_has_permissions')
        ->where('model_id', $user->id)
        ->where('permission_id', $permission->id)
        ->first();

    expect($pivot->expires_at)->toBeNull();

    // Verify user has the permission
    expect($user->hasPermissionTo('manage_schools'))->toBeTrue();
});

test('saving temporary permission stores override with expires_at timestamp', function () {
    $user = User::factory()->create([
        'role' => 'principal',
        'is_approved' => true,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('principal');

    $expiresAt = now()->addDays(7);

    // Simulate what EditUser::beforeSave() does: grant a temporary override
    $permission = Permission::where('name', 'manage_vaccinations')->first();
    $user->permissions()->attach($permission->id, ['expires_at' => $expiresAt]);
    $user->unsetRelation('permissions');

    // Verify the permission exists in the database
    expect(DB::table('model_has_permissions')
        ->where('model_id', $user->id)
        ->where('permission_id', $permission->id)
        ->exists())->toBeTrue();

    // Verify expires_at is set
    $pivot = DB::table('model_has_permissions')
        ->where('model_id', $user->id)
        ->where('permission_id', $permission->id)
        ->first();

    expect($pivot->expires_at)->not->toBeNull();

    // Verify user has the permission (not expired yet)
    expect($user->hasPermissionTo('manage_vaccinations'))->toBeTrue();
});

test('expired permissions are excluded from effective permissions', function () {
    $user = User::factory()->create([
        'role' => 'principal',
        'is_approved' => true,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('principal');

    // Directly assign an expired permission
    $expiredPermission = Permission::where('name', 'manage_vaccinations')->first();
    $user->permissions()->attach($expiredPermission->id, [
        'expires_at' => now()->subDays(1), // expired yesterday
    ]);

    $user->unsetRelation('permissions');

    // User should NOT have the expired permission
    expect($user->hasPermissionTo('manage_vaccinations'))->toBeFalse();

    // But the permission should still exist in the database
    expect(DB::table('model_has_permissions')
        ->where('model_id', $user->id)
        ->where('permission_id', $expiredPermission->id)
        ->exists())->toBeTrue();
});

test('changing role updates Spatie role assignment', function () {
    $user = User::factory()->create([
        'role' => 'principal',
        'is_approved' => true,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('principal');

    // Verify initial state
    expect($user->hasRole('principal'))->toBeTrue();
    expect($user->hasRole('health_coordinator'))->toBeFalse();

    // Simulate role change (what EditUser::beforeSave() does)
    $user->update(['role' => 'health_coordinator']);
    $user->syncRoles(['health_coordinator']);

    $user->refresh();

    // Verify the role was updated
    expect($user->role)->toBe('health_coordinator');
    expect($user->hasRole('health_coordinator'))->toBeTrue();
    expect($user->hasRole('principal'))->toBeFalse();

    // Verify permissions updated to match new role
    expect($user->hasPermissionTo('manage_vaccinations'))->toBeTrue();
    expect($user->hasPermissionTo('manage_absences'))->toBeTrue();
});

test('revoking direct permission removes it from model_has_permissions', function () {
    $user = User::factory()->create([
        'role' => 'principal',
        'is_approved' => true,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('principal');

    // Grant a direct permission
    $permission = Permission::where('name', 'manage_schools')->first();
    $user->permissions()->attach($permission->id, ['expires_at' => null]);
    $user->unsetRelation('permissions');

    expect($user->hasPermissionTo('manage_schools'))->toBeTrue();

    // Revoke the permission (what EditUser::beforeSave() does on uncheck)
    $user->permissions()->detach($permission->id);
    $user->unsetRelation('permissions');

    expect($user->hasPermissionTo('manage_schools'))->toBeFalse();
    expect(DB::table('model_has_permissions')
        ->where('model_id', $user->id)
        ->where('permission_id', $permission->id)
        ->exists())->toBeFalse();
});
