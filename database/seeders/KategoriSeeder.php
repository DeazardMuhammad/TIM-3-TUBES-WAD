<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            'Elektronik',
            'Pakaian',
            'Aksesoris',
            'Kunci',
            'Dokumen',
            'Tas/Ransel',
            'Sepatu',
            'Buku/Alat Tulis',
            'Olahraga',
            'Lainnya'
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create([
                'nama' => $kategori
            ]);
        }
    }
}
