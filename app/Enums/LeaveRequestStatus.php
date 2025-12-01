<?php

namespace App\Enums;

enum LeaveRequestStatus: string
{
    case Pending = 'pending';
    case ApprovedByLeader = 'approved_by_leader';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';

    /**
     * Label yang mudah dibaca untuk tampilan UI
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Konfirmasi',
            self::ApprovedByLeader => 'Diverifikasi Leader',
            self::Approved => 'Disetujui HRD',
            self::Rejected => 'Ditolak',
            self::Cancelled => 'Dibatalkan',
        };
    }

    /**
     * Kelas warna Tailwind CSS untuk Badge
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100 border-yellow-200 dark:border-yellow-700',
            self::ApprovedByLeader => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-700 border-blue-200 dark:border-blue-700',
            self::Approved => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 border-green-200 dark:border-green-700',
            self::Rejected => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 border-red-200 dark:border-red-700',
            self::Cancelled => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600',
        };
    }
}
