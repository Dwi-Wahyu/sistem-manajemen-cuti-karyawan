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
        User::factory(1)->admin()->create([
            'username' => 'admin',
            'name' => 'Administrator',
            'password' => 'password',
        ]);
        User::factory(4)->admin()->create([
            'password' => 'password',
        ]);

        // Buat 15 HRD
        User::factory(1)->hrd()->create([
            'username' => 'hrd',
            'name' => 'User Hrd',
            'password' => 'password',
        ]);
        User::factory(14)->hrd()->create([
            'password' => 'password',
        ]);

        // Buat 10 Divisi
        Division::factory(10)->create();
    }
}
