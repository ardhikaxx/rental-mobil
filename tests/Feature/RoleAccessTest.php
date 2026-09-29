<?php

use App\Enums\UserRole;
use App\Models\User;

function makeUser(UserRole $role): User
{
    return User::factory()->create(['role' => $role]);
}

it('blocks unauthenticated users from every module', function () {
    foreach ([
        '/dashboard', '/kendaraan', '/pelanggan', '/transaksi', '/pembayaran',
        '/kalender', '/serah-terima', '/pengembalian', '/pemeriksaan',
        '/perawatan', '/laporan', '/pengguna', '/audit-log', '/pengaturan',
    ] as $uri) {
        $this->get($uri)->assertRedirect(route('login'));
    }
});

it('lets staff access garage pages but blocks admin-only pages', function () {
    $staff = makeUser(UserRole::Staff);

    $this->actingAs($staff)->get('/dashboard')->assertOk();
    $this->actingAs($staff)->get('/serah-terima')->assertOk();
    $this->actingAs($staff)->get('/pengembalian')->assertOk();
    $this->actingAs($staff)->get('/pemeriksaan')->assertOk();
    $this->actingAs($staff)->get('/kendaraan')->assertOk();

    $this->actingAs($staff)->get('/pembayaran')->assertForbidden();
    $this->actingAs($staff)->get('/kalender')->assertForbidden();
    $this->actingAs($staff)->get('/transaksi/buat')->assertForbidden();
    $this->actingAs($staff)->get('/pengguna')->assertForbidden();
    $this->actingAs($staff)->get('/laporan')->assertForbidden();
    $this->actingAs($staff)->get('/pengaturan')->assertForbidden();
    $this->actingAs($staff)->get('/audit-log')->assertForbidden();
    $this->actingAs($staff)->get('/kendaraan/buat')->assertForbidden();
});

it('lets admin operate transactions but blocks owner-only pages', function () {
    $admin = makeUser(UserRole::Admin);

    $this->actingAs($admin)->get('/dashboard')->assertOk();
    $this->actingAs($admin)->get('/transaksi/buat')->assertOk();
    $this->actingAs($admin)->get('/pembayaran')->assertOk();
    $this->actingAs($admin)->get('/kalender')->assertOk();
    $this->actingAs($admin)->get('/pelanggan/buat')->assertOk();

    $this->actingAs($admin)->get('/pengguna')->assertForbidden();
    $this->actingAs($admin)->get('/laporan')->assertForbidden();
    $this->actingAs($admin)->get('/pengaturan')->assertForbidden();
    $this->actingAs($admin)->get('/audit-log')->assertForbidden();
    $this->actingAs($admin)->get('/kendaraan/buat')->assertForbidden();
});

it('gives the super admin access to every module', function () {
    $owner = makeUser(UserRole::SuperAdmin);

    foreach ([
        '/dashboard', '/kendaraan', '/kendaraan/buat', '/pelanggan', '/pelanggan/buat',
        '/transaksi', '/transaksi/buat', '/pembayaran', '/pembayaran/buat', '/kalender',
        '/serah-terima', '/pengembalian', '/pemeriksaan', '/pemeriksaan/baru',
        '/perawatan', '/perawatan/buat', '/laporan', '/pengguna', '/pengguna/buat',
        '/audit-log', '/pengaturan',
    ] as $uri) {
        $this->actingAs($owner)->get($uri)->assertOk();
    }
});

it('does not trust any role sent from the request', function () {
    $staff = makeUser(UserRole::Staff);

    $response = $this->actingAs($staff)->post('/pengguna', [
        'role' => UserRole::SuperAdmin->value,
        'name' => 'Hacker',
        'username' => 'hacker',
        'email' => 'hacker@test.local',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertForbidden();
    expect(User::where('username', 'hacker')->exists())->toBeFalse();
});

it('prevents a user from changing their own role through the update endpoint', function () {
    $staff = makeUser(UserRole::Staff);

    $this->actingAs($staff)
        ->put('/pengguna/'.$staff->id, [
            'name' => $staff->name,
            'username' => $staff->username,
            'email' => $staff->email,
            'role' => UserRole::SuperAdmin->value,
            'password' => '',
        ])
        ->assertForbidden();
});
