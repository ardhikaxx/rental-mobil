<?php

use App\Models\Transaction;
use App\Models\User;

it('shows the author credit on the login page', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee(config('app.author.name'))
        ->assertSee(config('app.author.url'));
});

it('renders the author credit in the sidebar footer for authenticated users', function () {
    $owner = User::factory()->superAdmin()->create();

    $this->actingAs($owner)->get(route('dashboard'))
        ->assertOk()
        ->assertSee(config('app.author.name'))
        ->assertSee(config('app.author.copyright'));
});

it('shows the author credit on error pages', function () {
    $this->get('/halaman-tidak-tersedia')
        ->assertNotFound()
        ->assertSee(config('app.author.name'));
});

it('renders the full copyright on the printable invoice', function () {
    $owner = User::factory()->superAdmin()->create();
    $transaction = Transaction::factory()->create();

    $this->actingAs($owner)->get(route('transactions.invoice', $transaction))
        ->assertOk()
        ->assertSee(config('app.author.copyright'));
});
