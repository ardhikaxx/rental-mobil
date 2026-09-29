<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Staff = 'staff';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin Operasional',
            self::Staff => 'Staf Garasi',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::SuperAdmin => 'badge-danger',
            self::Admin => 'badge-info',
            self::Staff => 'badge-secondary',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Pemilik bisnis. Akses penuh ke seluruh modul, laporan, pengguna, dan pengaturan.',
            self::Admin => 'Mengelola pelanggan, transaksi, pembayaran, dan jadwal operasional.',
            self::Staff => 'Menangani pemeriksaan, serah terima, pengembalian, dan perawatan kendaraan.',
        };
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
