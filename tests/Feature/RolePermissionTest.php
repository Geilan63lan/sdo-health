<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('sdo_admin can access admin panel', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create(['is_approved' => true]);
    $user->assignRole('sdo_admin');

    $this->actingAs($user);
    $response = $this->get('/admin');
    $response->assertSuccessful();
});

test('health_coordinator can access admin panel', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create(['is_approved' => true]);
    $user->assignRole('health_coordinator');

    $this->actingAs($user);
    $response = $this->get('/admin');
    $response->assertSuccessful();
});

test('principal can access admin panel', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create(['is_approved' => true]);
    $user->assignRole('principal');

    $this->actingAs($user);
    $response = $this->get('/admin');
    $response->assertSuccessful();
});

test('user without role cannot access admin panel', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();

    $this->actingAs($user);
    $response = $this->get('/admin');
    $response->assertForbidden();
});
