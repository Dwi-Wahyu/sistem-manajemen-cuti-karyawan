<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        // Buat DIVISI dan DIVISION HEAD
        // Kita buat 20 Divisi
        $divisions = Division::factory(20)->create()->each(function ($division) {
            // DivisionFactory membuat user di kolom head_user_id.
            $head = User::find($division->head_user_id);
            if ($head) {
                $head->update([
                    'role' => 'division_head',
                    'division_id' => $division->id,
                ]);
            }
        });

        // Buat 1000 EMPLOYEE
        // menyebar 1000 employee ke dalam 20 divisi.
        User::factory(1000)->create(function () use ($divisions) {
            return [
                'role' => 'employee',
                // Pilih satu divisi secara acak dari 20 divisi yang ada
                'division_id' => $divisions->random()->id,
            ];
        });
    }
}
