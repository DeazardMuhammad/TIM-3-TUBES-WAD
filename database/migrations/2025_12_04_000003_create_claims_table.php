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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Yang mengklaim
            $table->foreignId('found_item_id')->constrained('found_items')->onDelete('cascade'); // Barang yang diklaim
            $table->text('deskripsi_klaim'); // Alasan mengklaim
            $table->string('bukti')->nullable(); // Bukti kepemilikan (foto)
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->text('alasan_reject')->nullable(); // Alasan penolakan
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null'); // Admin yang mereview
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['found_item_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};

