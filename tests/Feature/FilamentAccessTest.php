<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('unapproved user is redirected from filament admin panel', function () {
    $user = User::factory()->create([
        'is_approved' => false,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('health_coordinator');

    actingAs($user)
        ->get('/admin')
        ->assertRedirect(route('pending-approval'));
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
