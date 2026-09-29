<?php

use App\Models\User;

it('redirects guests to the login page', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

it('shows the login page for guests', function () {
    $this->get(route('login'))->assertOk();
});

it('authenticates a user with valid credentials and regenerates the session', function () {
    $user = User::factory()->superAdmin()->create([
        'username' => 'superadmin',
        'password' => 'password123',
    ]);

    $response = $this->post(route('login.attempt'), [
        'username' => 'superadmin',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
});

it('rejects invalid credentials', function () {
    User::factory()->create(['username' => 'budi', 'password' => 'password123']);

    $response = $this->from(route('login'))->post(route('login.attempt'), [
        'username' => 'budi',
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});

it('rejects deactivated accounts even with correct credentials', function () {
    User::factory()->inactive()->create([
        'username' => 'dinonaktifkan',
        'password' => 'password123',
    ]);

    $response = $this->from(route('login'))->post(route('login.attempt'), [
        'username' => 'dinonaktifkan',
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});

it('logs the user out and invalidates the session', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('force logs out sessions of accounts deactivated after login', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user);

    $user->update(['is_active' => false]);

    $this->get(route('dashboard'))->assertRedirect(route('login'));
    $this->assertGuest();
});

it('stores passwords hashed, never plaintext', function () {
    $user = User::factory()->create(['password' => 'rahasia123']);

    expect($user->password)->not->toBe('rahasia123')
        ->and(password_verify('rahasia123', $user->password))->toBeTrue();
});
