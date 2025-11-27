<?php

namespace Database\Seeders;

// database/seeders/DatabaseSeeder.php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Division; // Jangan lupa import Division

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil LeaveTypeSeeder
        $this->call(LeaveTypeSeeder::class);

        // Membuat Akun Admin
        User::firstOrCreate(
            ['email' => 'admin@cuti.com'],
            [
                'username' => 'admin',
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'join_date' => now()->subYears(5),
                'current_annual_leave_quota' => 999,
                'initial_annual_leave_quota' => 999,
                'is_active' => true,
            ]
        );

        // Membuat Akun HRD
        User::firstOrCreate(
            ['email' => 'ahmadhidayat@email.com'],
            [
                'username' => 'arilpalu',
                'name' => 'Ahmad Hidayat',
                'password' => Hash::make('password'),
                'role' => 'hrd',
                'join_date' => now()->subYears(2),
                'is_active' => true,
            ]
        );
        
        // Buat contoh Divisi dan Ketua Divisi
        $headDev = User::firstOrCreate(
            ['email' => 'wahyuilahi@email.com'],
            [
                'username' => 'wahil',
                'name' => 'Wahyu Ilahi',
                'password' => Hash::make('password'),
                'role' => 'division_head',
                'join_date' => now()->subYears(1),
                'is_active' => true,
            ]
        );

        $divisionDev = Division::firstOrCreate(
            ['name' => 'Pengembangan Software'],
            [
                'description' => 'Divisi yang bertanggung jawab atas pengembangan aplikasi.',
                'head_user_id' => $headDev->id,
                'established_date' => now()->subMonths(6),
            ]
        );
        
        // Update user headDev agar memiliki division_id
        $headDev->division_id = $divisionDev->id;
        $headDev->save();


        // Contoh Karyawan (Role Employee)
        User::firstOrCreate(
            ['email' => 'jokowi@email.com'],
            [
                'username' => 'jokododo',
                'name' => 'Jokowi',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'division_id' => $divisionDev->id, // Assign ke Divisi Dev
                'join_date' => now()->subMonths(6),
            ]
        );
    }
}