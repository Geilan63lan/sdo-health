<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Make address and level nullable
            $table->string('address')->nullable()->change();
            $table->enum('level', ['elementary', 'jhs', 'shs', 'integrated'])->nullable()->change();
        });

        // Clear the data
        \App\Models\School::query()->update([
            'address' => null,
            'level' => null,
        ]);
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('address')->nullable(false)->change();
            $table->enum('level', ['elementary', 'jhs', 'shs', 'integrated'])->nullable(false)->change();
        });
    }
};
