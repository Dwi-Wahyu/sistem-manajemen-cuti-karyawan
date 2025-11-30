<?php

namespace Database\Factories;

use App\Models\Division;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            // Username unik berdasarkan nama
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),

            // --- Kolom Tambahan Sesuai Model User ---
            'role' => 'employee', // Default role
            'phone_number' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'join_date' => fake()->dateTimeBetween('-5 years', 'now'),
            'initial_annual_leave_quota' => 12,
            'current_annual_leave_quota' => 12,
            'is_active' => true,

            // PENTING:
            // Kita gunakan Division::factory() sebagai default. 
            // Namun, saat Seeding massal, kita akan menimpa (override) nilai ini
            // agar tidak membuat 1 divisi baru untuk setiap 1 user.
            'division_id' => Division::factory(),
        ];
    }

    // State untuk membuat Admin dengan mudah
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
            'division_id' => null, // Admin biasanya tidak terikat divisi tertentu
        ]);
    }

    // State untuk HRD
    public function hrd(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'hrd',
            'division_id' => null, // HRD bisa independen atau punya divisi sendiri
        ]);
    }

    // State untuk Division Head
    public function divisionHead(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'division_head',
        ]);
    }
}
