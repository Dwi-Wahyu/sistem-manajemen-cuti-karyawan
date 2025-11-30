<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Hrd = 'hrd';
    case DivisionHead = 'division_head';
    case Employee = 'employee';

    /**
     * Mengembalikan judul yang mudah dibaca untuk header halaman.
     * @return string
     */
    public function title(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Hrd => 'HRD',
            self::DivisionHead => 'Kepala Divisi',
            self::Employee => 'Karyawan',
        };
    }
}
