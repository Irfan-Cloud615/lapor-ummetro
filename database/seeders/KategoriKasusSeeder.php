<?php

namespace Database\Seeders;

use App\Models\KategoriKasus;
use Illuminate\Database\Seeder;

class KategoriKasusSeeder extends Seeder
{
    /**
     * Kategori awal untuk pilihan select2 pada form pelaporan perundungan.
     */
    public function run(): void
    {
        $kategoris = [
            'Perundungan Fisik',
            'Perundungan Verbal',
            'Perundungan Sosial (Pengucilan)',
            'Perundungan Siber / Daring',
            'Perundungan Relasi',
            'Ekstorsi / Pemerasan',
            'Kekerasan Berbasis Gender (KBG)',
            'Lainnya',
        ];

        foreach ($kategoris as $nama) {
            KategoriKasus::firstOrCreate(
                ['nama_kategori' => $nama],
                ['is_active' => true]
            );
        }
    }
}
