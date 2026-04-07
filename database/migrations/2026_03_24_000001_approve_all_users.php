<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Approve all existing users
        DB::table('users')->update(['is_approved' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert approval status
        DB::table('users')->update(['is_approved' => false]);
    }
};
