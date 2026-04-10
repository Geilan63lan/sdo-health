<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_history_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('grade_level');
            $table->boolean('has_allergies')->default(false);
            $table->json('allergy_types')->nullable();
            $table->text('allergy_others')->nullable();
            $table->boolean('has_medical_conditions')->default(false);
            $table->json('condition_types')->nullable();
            $table->text('condition_others')->nullable();
            $table->boolean('has_past_surgery')->default(false);
            $table->text('surgery_details')->nullable();
            $table->json('family_history')->nullable();
            $table->string('cancer_type')->nullable();
            $table->text('family_history_other')->nullable();
            $table->boolean('smoke_exposure')->default(false);
            $table->string('dominant_hand')->nullable();
            $table->boolean('validated')->default(false);
            $table->timestamp('validated_at')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('invalidated_at')->nullable();
            $table->foreignId('invalidated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique(['student_id', 'grade_level']);
            $table->index('student_id');
            $table->index('grade_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_history_items');
    }
};
