<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Division;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ID Divisi yang sudah ada
        $divisionIds = Division::pluck('id');

        if ($divisionIds->isEmpty()) {
            $this->command->error('Tidak ada Divisi ditemukan! Jalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        // Konfigurasi Jumlah
        $totalEmployee = 1000; // Target total
        $chunkSize = 50;

        $this->command->info("Memulai generate $totalEmployee karyawan...");

        // Loop pembuatan user (Teknik Chunking)
        $count = 0;
        for ($i = 0; $i < $totalEmployee; $i += $chunkSize) {

            User::factory($chunkSize)->create(function () use ($divisionIds) {
                return [
                    'role' => 'employee',
                    // Ambil 1 ID divisi secara acak
                    'division_id' => $divisionIds->random(),
                ];
            });

            $count += $chunkSize;
            $this->command->info("Berhasil membuat $count karyawan...");
        }

        $this->command->info("Selesai! $totalEmployee karyawan telah ditambahkan.");
    }
}
