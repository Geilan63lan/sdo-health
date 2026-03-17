<?php

use App\Models\School;

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    $school = School::factory()->create();

    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'school_id' => $school->id,
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('filament.admin.pages.dashboard', absolute: false));

    $this->assertAuthenticated();
});
