<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('found_items', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Nama barang yang ditemukan
            $table->string('lokasi'); // Lokasi barang ditemukan
            $table->date('tanggal'); // Tanggal penemuan
            $table->string('kontak'); // Kontak penemu
            $table->text('deskripsi')->nullable(); // Deskripsi detail barang
            $table->string('gambar')->nullable(); // Path gambar barang
            $table->enum('status', ['belum diambil', 'sudah diambil'])->default('belum diambil');
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade'); // FK ke kategori
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // FK ke user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('found_items');
    }
};
