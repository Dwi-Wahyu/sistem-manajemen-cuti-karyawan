<?php

namespace Database\Seeders;

// database/seeders/LeaveTypeSeeder.php

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Cuti Tahunan: Mengurangi kuota (is_quota_deductible = true)
        LeaveType::firstOrCreate(
            ['name' => 'Cuti Tahunan'],
            [
                'description' => 'Cuti yang diberikan sebanyak 12 hari kerja per tahun.',
                'is_quota_deductible' => true,
            ]
        );

        // 2. Cuti Sakit: Tidak mengurangi kuota (is_quota_deductible = false)
        LeaveType::firstOrCreate(
            ['name' => 'Cuti Sakit'],
            [
                'description' => 'Cuti yang diajukan dengan lampiran surat dokter dan tidak mengurangi kuota tahunan.',
                'is_quota_deductible' => false,
            ]
        );
    }
}