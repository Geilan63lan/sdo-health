<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_clinics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('clinic_name');
            $table->string('location');
            $table->string('head_nurse_name')->nullable();
            $table->string('nurse_contact')->nullable();
            $table->integer('bed_count')->default(0);
            $table->text('equipment_inventory')->nullable();
            $table->text('operating_hours')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_clinics');
    }
};
