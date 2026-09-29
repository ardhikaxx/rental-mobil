<?php

use App\Enums\UserRole;
use App\Models\User;

it('shows a show-hide toggle on the login password field', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('type="password"', false)
        ->assertSee('data-password-toggle="password"', false)
        ->assertSee('fa-regular fa-eye', false);
});

it('shows a toggle on both password fields of the create and edit user forms', function () {
    $owner = User::factory()->superAdmin()->create();

    $this->actingAs($owner)->get(route('users.create'))
        ->assertOk()
        ->assertSee('data-password-toggle="password"', false)
        ->assertSee('data-password-toggle="password_confirmation"', false);

    $this->actingAs($owner)->get(route('users.edit', $owner))
        ->assertOk()
        ->assertSee('data-password-toggle="password"', false)
        ->assertSee('data-password-toggle="password_confirmation"', false);
});

it('keeps password validation errors visible inside the input group', function () {
    $owner = User::factory()->superAdmin()->create();

    $this->actingAs($owner)
        ->from(route('users.create'))
        ->followingRedirects()
        ->post(route('users.store'), [
            'name' => 'Uji Coba',
            'username' => 'ujicoba',
            'email' => 'uji@rental.test',
            'role' => UserRole::Staff->value,
            'password' => 'pendek',
            'password_confirmation' => 'pendek',
        ])
        ->assertOk()
        ->assertSee('is-invalid', false)
        ->assertSee('has-validation', false)
        ->assertSee('data-password-toggle="password"', false);
});

it('ships the toggle behaviour in the application script', function () {
    expect(file_get_contents(public_path('js/app.js')))
        ->toContain('initPasswordToggles', 'data-password-toggle');
});
