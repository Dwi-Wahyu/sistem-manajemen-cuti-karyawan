<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 5 ADMIN
        User::factory(5)->admin()->create([
            'password' => 'password',
        ]);

        // Buat 15 HRD
        User::factory(15)->hrd()->create([
            'password' => 'password',
        ]);

        // Buat 10 division_head
        User::factory(10)->divisionHead()->create([
            'password' => 'password',
        ]);

        // Buat 10 Divisi
        Division::factory(10)->create()->each(function ($division) {
            $head = User::where('role', 'division_head')
                ->whereNull('division_id') // Cari yang division_id-nya NULL
                ->first();
            if ($head) {
                $head->update([
                    'role' => 'division_head',
                    'division_id' => null,
                ]);
            }
        });
    }
}
