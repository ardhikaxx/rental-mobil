<?php

use App\Models\User;
use App\Support\Navigation;

test('guest is redirected to login when accessing guide', function () {
    $this->get(route('guide.index'))->assertRedirect(route('login'));
});

test('super admin can access guide and see role badge and all sections', function () {
    $superAdmin = User::factory()->superAdmin()->create();

    $response = $this->actingAs($superAdmin)->get(route('guide.index'));

    $response->assertOk();
    $response->assertViewIs('guide.index');
    $response->assertSee('Super Admin');
    $response->assertSee('id="sa-ringkasan"', false);
    $response->assertSee('id="admin-transaksi"', false);
    $response->assertSee('id="staff-serah-terima"', false);
    $response->assertSee('id="matriks-role"', false);
});

test('admin can access guide and see operational sections', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('guide.index'));

    $response->assertOk();
    $response->assertViewIs('guide.index');
    $response->assertSee('Admin');
    $response->assertSee('id="admin-transaksi"', false);
    $response->assertSee('id="admin-kalender"', false);
    $response->assertSee('id="staff-serah-terima"', false);
    $response->assertDontSee('id="sa-ringkasan"', false);
});

test('staff can access guide and see field garage operations', function () {
    $staff = User::factory()->staff()->create();

    $response = $this->actingAs($staff)->get(route('guide.index'));

    $response->assertOk();
    $response->assertViewIs('guide.index');
    $response->assertSee('Petugas Lapangan');
    $response->assertSee('id="staff-serah-terima"', false);
    $response->assertSee('id="staff-pemeriksaan"', false);
    $response->assertSee('id="staff-pengembalian"', false);
    $response->assertDontSee('id="sa-ringkasan"', false);
});

test('navigation includes guide menu item for all roles', function () {
    $superAdmin = User::factory()->superAdmin()->create();
    $admin = User::factory()->admin()->create();
    $staff = User::factory()->staff()->create();

    $superAdminNav = Navigation::for($superAdmin);
    $adminNav = Navigation::for($admin);
    $staffNav = Navigation::for($staff);

    expect(collect($superAdminNav)->pluck('route'))->toContain('guide.index');
    expect(collect($adminNav)->pluck('route'))->toContain('guide.index');
    expect(collect($staffNav)->pluck('route'))->toContain('guide.index');
});
