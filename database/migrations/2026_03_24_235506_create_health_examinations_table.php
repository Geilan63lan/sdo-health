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
        Schema::create('health_examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('examined_by')->constrained('users');

            // 1. Metadata & Grade Context
            $table->string('grade_level');
            $table->date('date_of_examination');
            $table->string('designation')->nullable();

            // 2. Physical Growth
            $table->decimal('height_cm', 5, 2);
            $table->decimal('weight_kg', 5, 2);
            $table->string('ns_bmi_for_age');
            $table->string('ns_height_for_age');

            // 3. Interventions (Standardized Months)
            $table->boolean('is_4ps_beneficiary')->default(false);
            $table->boolean('is_sbfp_beneficiary')->default(false);
            $table->boolean('deworming_july')->default(false);
            $table->boolean('deworming_january')->default(false);
            $table->boolean('iron_supplementation')->default(false);
            $table->string('immunization_kind')->nullable();

            // 4. Vitals & Development
            $table->string('menarche')->nullable();
            $table->string('temperature')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->string('pulse_rate')->nullable();
            $table->string('respiratory_rate')->nullable();

            // 5. Sensory Screenings (L/R)
            $table->char('vision_l', 1)->nullable();
            $table->char('vision_r', 1)->nullable();
            $table->char('auditory_l', 1)->nullable();
            $table->char('auditory_r', 1)->nullable();

            // 6. Systematic Findings (Mapped to Legend Letters)
            $table->string('skin_scalp')->nullable();
            $table->string('eyes_ears_nose')->nullable();
            $table->string('mouth_neck_throat')->nullable();
            $table->string('lungs_heart')->nullable();
            $table->string('abdomen')->nullable();
            $table->string('deformities')->nullable();

            $table->text('others_specify')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_examinations');
    }
};
