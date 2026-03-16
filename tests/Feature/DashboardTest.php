<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('guests are redirected to the login page', function () {
    $response = $this->get(route('filament.admin.pages.dashboard'));
    $response->assertRedirect(route('filament.admin.auth.login'));
});

test('authenticated users can visit the admin panel', function () {
    $user = User::factory()->create(['is_approved' => true]);
    $user->assignRole('sdo_admin');
    $this->actingAs($user);

    $response = $this->get(route('filament.admin.pages.dashboard'));
    $response->assertOk();
});
