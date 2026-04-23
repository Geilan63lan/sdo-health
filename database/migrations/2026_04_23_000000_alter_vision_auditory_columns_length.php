<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('health_examinations', function (Blueprint $table) {
            $table->string('vision_l', 50)->nullable()->change();
            $table->string('vision_r', 50)->nullable()->change();
            $table->string('auditory_l', 50)->nullable()->change();
            $table->string('auditory_r', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('health_examinations', function (Blueprint $table) {
            $table->char('vision_l', 1)->nullable()->change();
            $table->char('vision_r', 1)->nullable()->change();
            $table->char('auditory_l', 1)->nullable()->change();
            $table->char('auditory_r', 1)->nullable()->change();
        });
    }
};