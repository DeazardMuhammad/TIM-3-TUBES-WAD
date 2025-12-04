<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kategori;
use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\Notification;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Use this seeder to create test data for the Lost & Found system
     * 
     * Run with: php artisan db:seed --class=TestDataSeeder
     */
    public function run(): void
    {
        // Create test users if they don't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'nama' => 'Admin Test',
                'nim' => 'ADM001',
                'kontak' => '081234567890',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]
        );

        $user1 = User::firstOrCreate(
            ['email' => 'user1@test.com'],
            [
                'nama' => 'User Test 1',
                'nim' => 'USR001',
                'kontak' => '081234567891',
                'role' => 'mahasiswa',
                'password' => bcrypt('password'),
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'user2@test.com'],
            [
                'nama' => 'User Test 2',
                'nim' => 'USR002',
                'kontak' => '081234567892',
                'role' => 'mahasiswa',
                'password' => bcrypt('password'),
            ]
        );

        // Get existing categories
        $categories = Kategori::all();

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found! Please run KategoriSeeder first.');
            return;
        }

        // Create some pending lost items
        LostItem::create([
            'nama' => 'Laptop ASUS ROG',
            'lokasi' => 'Ruang Kuliah A301',
            'tanggal' => now()->subDays(2),
            'kontak' => $user1->kontak,
            'deskripsi' => 'Laptop gaming warna hitam dengan sticker anime',
            'status' => 'belum diambil',
            'status_verifikasi' => 'pending',
            'kategori_id' => $categories->first()->id,
            'user_id' => $user1->id,
        ]);

        // Create some approved found items
        FoundItem::create([
            'nama' => 'Dompet Kulit Coklat',
            'lokasi' => 'Kantin Lantai 2',
            'tanggal' => now()->subDays(1),
            'kontak' => $user2->kontak,
            'deskripsi' => 'Dompet kulit coklat berisi KTP dan kartu ATM',
            'status' => 'belum diambil',
            'status_verifikasi' => 'approved',
            'verified_by' => $admin->id,
            'verified_at' => now(),
            'kategori_id' => $categories->skip(1)->first()->id ?? $categories->first()->id,
            'user_id' => $user2->id,
        ]);

        // Create a notification for user1
        Notification::create([
            'user_id' => $user1->id,
            'title' => 'Selamat Datang!',
            'message' => 'Selamat datang di sistem Lost & Found. Laporkan barang hilang atau ditemukan Anda di sini.',
            'link' => route('dashboard'),
            'type' => 'info',
            'read_status' => false,
        ]);

        $this->command->info('✓ Test data created successfully!');
        $this->command->info('');
        $this->command->info('Test Accounts:');
        $this->command->info('Admin: admin@test.com / password');
        $this->command->info('User 1: user1@test.com / password');
        $this->command->info('User 2: user2@test.com / password');
    }
}

