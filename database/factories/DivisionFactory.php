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
        $divisions = [
            'Sumber Daya Manusia' => 'Bertanggung jawab atas rekrutmen, pelatihan, dan kesejahteraan karyawan.',
            'Keuangan & Akuntansi' => 'Mengelola arus kas, penggajian, pajak, dan laporan keuangan perusahaan.',
            'Teknologi Informasi' => 'Mengembangkan dan memelihara sistem komputer, jaringan, dan keamanan data.',
            'Pemasaran & Branding' => 'Merancang strategi promosi, iklan, dan membangun citra perusahaan.',
            'Penjualan & Bisnis' => 'Fokus pada pencapaian target penjualan dan ekspansi pasar baru.',
            'Operasional' => 'Memastikan kegiatan operasional harian berjalan efektif dan efisien.',
            'Riset & Pengembangan' => 'Melakukan inovasi produk serta penelitian untuk pengembangan layanan.',
            'Layanan Pelanggan' => 'Menangani pertanyaan, keluhan, dan memastikan kepuasan pelanggan.',
            'Legal & Kepatuhan' => 'Menangani kontrak kerja, aspek hukum, dan regulasi perusahaan.',
            'Umum & Logistik' => 'Mengelola inventaris kantor, fasilitas gedung, dan pengadaan barang.',
        ];

        $name = $this->faker->unique()->randomElement(array_keys($divisions));

        return [
            'name' => $name,

            'description' => $divisions[$name],

            'head_user_id' => User::factory()->divisionHead(),

            'established_date' => $this->faker->date(),
        ];
    }
}
