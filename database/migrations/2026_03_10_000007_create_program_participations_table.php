<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('health_program_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('participation_date');
            $table->text('outcome')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['health_program_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_participations');
    }
};
