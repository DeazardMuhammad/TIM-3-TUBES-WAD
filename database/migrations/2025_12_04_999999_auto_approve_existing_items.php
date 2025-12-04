<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Auto-approve all existing items for backward compatibility
     */
    public function up(): void
    {
        // Update lost items
        DB::table('lost_items')
            ->where('status_verifikasi', 'pending')
            ->update([
                'status_verifikasi' => 'approved',
                'verified_at' => now(),
            ]);

        // Update found items
        DB::table('found_items')
            ->where('status_verifikasi', 'pending')
            ->update([
                'status_verifikasi' => 'approved',
                'verified_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse
    }
};

