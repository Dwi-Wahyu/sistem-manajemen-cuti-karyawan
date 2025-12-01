<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Hari Libur Nasional & Cuti Bersama Tahun 2025

        $holidays = [
            // --- JANUARI ---
            [
                'date' => '2025-01-01',
                'name' => 'Tahun Baru 2025 Masehi',
                'is_joint_leave' => false,
            ],
            [
                'date' => '2025-01-27',
                'name' => 'Isra Mikraj Nabi Muhammad SAW',
                'is_joint_leave' => false,
            ],
            [
                'date' => '2025-01-28',
                'name' => 'Cuti Bersama Tahun Baru Imlek',
                'is_joint_leave' => true,
            ],
            [
                'date' => '2025-01-29',
                'name' => 'Tahun Baru Imlek 2576 Kongzili',
                'is_joint_leave' => false,
            ],

            // --- MARET ---
            [
                'date' => '2025-03-28',
                'name' => 'Cuti Bersama Hari Suci Nyepi',
                'is_joint_leave' => true,
            ],
            [
                'date' => '2025-03-29',
                'name' => 'Hari Suci Nyepi Tahun Baru Saka 1947',
                'is_joint_leave' => false,
            ],
            [
                'date' => '2025-03-31',
                'name' => 'Hari Raya Idul Fitri 1446 Hijriah',
                'is_joint_leave' => false,
            ],

            // --- APRIL (Idul Fitri) ---
            [
                'date' => '2025-04-01',
                'name' => 'Hari Raya Idul Fitri 1446 Hijriah',
                'is_joint_leave' => false,
            ],
            // Cuti Bersama Idul Fitri (Simulasi: biasanya 4 hari)
            [
                'date' => '2025-04-02',
                'name' => 'Cuti Bersama Idul Fitri 1446 H',
                'is_joint_leave' => true,
            ],
            [
                'date' => '2025-04-03',
                'name' => 'Cuti Bersama Idul Fitri 1446 H',
                'is_joint_leave' => true,
            ],
            [
                'date' => '2025-04-04',
                'name' => 'Cuti Bersama Idul Fitri 1446 H',
                'is_joint_leave' => true,
            ],
            [
                'date' => '2025-04-07',
                'name' => 'Cuti Bersama Idul Fitri 1446 H',
                'is_joint_leave' => true,
            ],

            // --- MEI ---
            [
                'date' => '2025-05-01',
                'name' => 'Hari Buruh Internasional',
                'is_joint_leave' => false,
            ],
            [
                'date' => '2025-05-12',
                'name' => 'Hari Raya Waisak 2569 BE',
                'is_joint_leave' => false,
            ],
            [
                'date' => '2025-05-13',
                'name' => 'Cuti Bersama Hari Raya Waisak',
                'is_joint_leave' => true,
            ],
            [
                'date' => '2025-05-29',
                'name' => 'Kenaikan Isa Almasih',
                'is_joint_leave' => false,
            ],
            [
                'date' => '2025-05-30',
                'name' => 'Cuti Bersama Kenaikan Isa Almasih',
                'is_joint_leave' => true,
            ],

            // --- JUNI ---
            [
                'date' => '2025-06-01',
                'name' => 'Hari Lahir Pancasila',
                'is_joint_leave' => false,
            ],
            [
                'date' => '2025-06-06',
                'name' => 'Hari Raya Idul Adha 1446 Hijriah',
                'is_joint_leave' => false,
            ],
            [
                'date' => '2025-06-27',
                'name' => 'Tahun Baru Islam 1447 Hijriah',
                'is_joint_leave' => false,
            ],

            // --- AGUSTUS ---
            [
                'date' => '2025-08-17',
                'name' => 'Hari Kemerdekaan Republik Indonesia',
                'is_joint_leave' => false,
            ],

            // --- SEPTEMBER ---
            [
                'date' => '2025-09-05',
                'name' => 'Maulid Nabi Muhammad SAW',
                'is_joint_leave' => false,
            ],

            // --- DESEMBER ---
            [
                'date' => '2025-12-25',
                'name' => 'Hari Raya Natal',
                'is_joint_leave' => false,
            ],
            [
                'date' => '2025-12-26',
                'name' => 'Cuti Bersama Hari Raya Natal',
                'is_joint_leave' => true,
            ],
        ];

        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                ['date' => $holiday['date']], // Kunci pencarian (agar tidak duplikat)
                $holiday // Data yang akan diinsert/update
            );
        }
    }
}
