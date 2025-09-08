<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat admin default
        User::create([
            'nama' => 'Administrator',
            'nim' => 'admin',
            'email' => 'admin@lostandfound.com',
            'kontak' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        // Buat mahasiswa contoh
        User::create([
            'nama' => 'Mahasiswa Test',
            'nim' => '12345678',
            'email' => 'mahasiswa@test.com',
            'kontak' => '081234567891',
            'role' => 'mahasiswa',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
