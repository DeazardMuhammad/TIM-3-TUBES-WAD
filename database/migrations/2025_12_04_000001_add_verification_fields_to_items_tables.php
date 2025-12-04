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
        // Add verification fields to lost_items table
        Schema::table('lost_items', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->text('alasan_reject')->nullable()->after('status_verifikasi');
            $table->timestamp('verified_at')->nullable()->after('alasan_reject');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null')->after('verified_at');
        });

        // Add verification fields to found_items table
        Schema::table('found_items', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->text('alasan_reject')->nullable()->after('status_verifikasi');
            $table->timestamp('verified_at')->nullable()->after('alasan_reject');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null')->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lost_items', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['status_verifikasi', 'alasan_reject', 'verified_at', 'verified_by']);
        });

        Schema::table('found_items', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['status_verifikasi', 'alasan_reject', 'verified_at', 'verified_by']);
        });
    }
};

