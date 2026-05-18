<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\NewUserWaitingApproval;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('unapproved user is redirected to pending approval page', function () {
    $user = User::factory()->create([
        'is_approved' => false,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('health_coordinator');

    actingAs($user)
        ->get('/admin')
        ->assertRedirect(route('pending-approval'));

    actingAs($user)
        ->get(route('pending-approval'))
        ->assertOk()
        ->assertSee('Account Pending Approval');
});

test('approved user can access admin panel', function () {
    $user = User::factory()->create([
        'is_approved' => true,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('health_coordinator');

    actingAs($user)
        ->get('/admin')
        ->assertOk();
});

test('sdo_admin is not redirected even if unapproved', function () {
    $user = User::factory()->create([
        'is_approved' => false,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('sdo_admin');

    actingAs($user)
        ->get('/admin')
        ->assertOk();
});

test('new user registration triggers notification to admins', function () {
    Notification::fake();

    $admin = User::factory()->create([
        'email' => 'admin@test.com',
        'is_approved' => true,
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('sdo_admin');

    $school = \App\Models\School::factory()->create();

    $creator = new \App\Actions\Fortify\CreateNewUser;
    $user = $creator->create([
        'name' => 'New User',
        'email' => 'new@test.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'school_id' => $school->id,
    ]);

    Notification::assertSentTo(
        $admin,
        NewUserWaitingApproval::class,
        function ($notification) use ($user) {
            return $notification->user->id === $user->id;
        }
    );
});
