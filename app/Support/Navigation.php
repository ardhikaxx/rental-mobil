<?php

namespace App\Support;

use App\Enums\UserRole;
use App\Models\User;

class Navigation
{
    /**
     * Sidebar menu definition filtered by the authenticated user's role.
     *
     * @return array<int, array{label: string, route: string, icon: string, match: string}>
     */
    public static function for(User $user): array
    {
        $items = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'fa-gauge-high', 'match' => 'dashboard', 'roles' => []],
            ['label' => 'Transaksi', 'route' => 'transactions.index', 'icon' => 'fa-file-invoice', 'match' => 'transactions*', 'roles' => ['super_admin', 'admin', 'staff']],
            ['label' => 'Serah Terima', 'route' => 'handover.index', 'icon' => 'fa-key', 'match' => 'handover*', 'roles' => ['super_admin', 'admin', 'staff']],
            ['label' => 'Pengembalian', 'route' => 'return.index', 'icon' => 'fa-rotate-left', 'match' => 'return*', 'roles' => ['super_admin', 'admin', 'staff']],
            ['label' => 'Pelanggan', 'route' => 'customers.index', 'icon' => 'fa-users', 'match' => 'customers*', 'roles' => ['super_admin', 'admin', 'staff']],
            ['label' => 'Kendaraan', 'route' => 'vehicles.index', 'icon' => 'fa-car-side', 'match' => 'vehicles*', 'roles' => ['super_admin', 'admin', 'staff']],
            ['label' => 'Pembayaran', 'route' => 'payments.index', 'icon' => 'fa-money-bill-transfer', 'match' => 'payments*', 'roles' => ['super_admin', 'admin']],
            ['label' => 'Kalender Booking', 'route' => 'calendar.index', 'icon' => 'fa-calendar-days', 'match' => 'calendar*', 'roles' => ['super_admin', 'admin']],
            ['label' => 'Pemeriksaan', 'route' => 'inspections.index', 'icon' => 'fa-clipboard-check', 'match' => 'inspections*', 'roles' => ['super_admin', 'admin', 'staff']],
            ['label' => 'Perawatan', 'route' => 'maintenances.index', 'icon' => 'fa-screwdriver-wrench', 'match' => 'maintenances*', 'roles' => ['super_admin', 'staff']],
            ['label' => 'Laporan', 'route' => 'reports.index', 'icon' => 'fa-chart-column', 'match' => 'reports*', 'roles' => ['super_admin']],
            ['label' => 'Pengguna', 'route' => 'users.index', 'icon' => 'fa-user-shield', 'match' => 'users*', 'roles' => ['super_admin']],
            ['label' => 'Audit Log', 'route' => 'audit-logs.index', 'icon' => 'fa-clock-rotate-left', 'match' => 'audit-logs*', 'roles' => ['super_admin']],
            ['label' => 'Pengaturan', 'route' => 'settings.index', 'icon' => 'fa-gear', 'match' => 'settings*', 'roles' => ['super_admin']],
        ];

        $role = $user->role->value;

        return array_values(array_filter(
            $items,
            fn (array $item) => $item['roles'] === [] || in_array($role, $item['roles'], true),
        ));
    }

    /**
     * @return array<int, string>
     */
    public static function allowedRoles(string $ability): array
    {
        return match ($ability) {
            'manage-users', 'view-reports', 'view-audit-log', 'manage-settings' => [UserRole::SuperAdmin->value],
            'manage-payments', 'view-calendar' => [UserRole::SuperAdmin->value, UserRole::Admin->value],
            default => [UserRole::SuperAdmin->value, UserRole::Admin->value, UserRole::Staff->value],
        };
    }
}
