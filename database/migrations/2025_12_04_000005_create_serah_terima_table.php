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
        Schema::create('serah_terima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->unique()->constrained('claims')->onDelete('cascade');
            $table->string('bukti_serah_terima_user')->nullable(); // Foto bukti dari user
            $table->string('bukti_serah_terima_admin')->nullable(); // Foto bukti dari admin
            $table->timestamp('user_uploaded_at')->nullable();
            $table->timestamp('admin_uploaded_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['pending', 'user_uploaded', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serah_terima');
    }
};

