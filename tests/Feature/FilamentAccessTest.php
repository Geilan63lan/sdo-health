<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup roles
    Role::create(['name' => 'health_coordinator']);
    Role::create(['name' => 'sdo_admin']);
    Role::create(['name' => 'principal']);
});

test('unapproved user cannot access filament admin panel', function () {
    $user = User::factory()->create([
        'is_approved' => false,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('health_coordinator');

    actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

test('unverified user cannot access filament admin panel', function () {
    $user = User::factory()->create([
        'is_approved' => true,
        'email_verified_at' => null,
    ]);
    $user->assignRole('health_coordinator');

    actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

test('approved and verified user can access filament admin panel', function () {
    $user = User::factory()->create([
        'is_approved' => true,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('health_coordinator');

    actingAs($user)
        ->get('/admin')
        ->assertOk();
});

test('unverified sdo_admin cannot access filament admin panel', function () {
    $user = User::factory()->create([
        'is_approved' => true,
        'email_verified_at' => null,
    ]);
    $user->assignRole('sdo_admin');

    actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

test('verified but unapproved sdo_admin can access filament admin panel', function () {
    $user = User::factory()->create([
        'is_approved' => false,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('sdo_admin');

    actingAs($user)
        ->get('/admin')
        ->assertOk();
});
