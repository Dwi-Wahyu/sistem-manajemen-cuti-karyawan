<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Division>
 */
class DivisionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Membuat nama divisi acak (contoh: "Creative Solutions")
            'name' => ucwords($this->faker->unique()->words(2, true)),

            // Deskripsi singkat
            'description' => $this->faker->sentence(),

            // Tanggal acak di masa lalu
            'established_date' => $this->faker->date(),
        ];
    }
}
