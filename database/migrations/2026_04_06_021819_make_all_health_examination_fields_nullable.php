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
        Schema::table('health_examinations', function (Blueprint $table) {
            $table->date('date_of_examination')->nullable()->change();
            $table->decimal('height_cm', 5, 2)->nullable()->change();
            $table->decimal('weight_kg', 5, 2)->nullable()->change();
            $table->string('ns_bmi_for_age')->nullable()->change();
            $table->string('ns_height_for_age')->nullable()->change();
            $table->string('immunization_kind')->nullable()->change();
            $table->string('menarche')->nullable()->change();
            $table->string('temperature')->nullable()->change();
            $table->string('blood_pressure')->nullable()->change();
            $table->string('pulse_rate')->nullable()->change();
            $table->string('respiratory_rate')->nullable()->change();
            $table->char('vision_l', 1)->nullable()->change();
            $table->char('vision_r', 1)->nullable()->change();
            $table->char('auditory_l', 1)->nullable()->change();
            $table->char('auditory_r', 1)->nullable()->change();
            $table->string('skin_scalp')->nullable()->change();
            $table->string('eyes_ears_nose')->nullable()->change();
            $table->string('mouth_neck_throat')->nullable()->change();
            $table->string('lungs_heart')->nullable()->change();
            $table->string('abdomen')->nullable()->change();
            $table->string('deformities')->nullable()->change();
            $table->text('others_specify')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_examinations', function (Blueprint $table) {
            $table->date('date_of_examination')->nullable(false)->change();
            $table->decimal('height_cm', 5, 2)->nullable(false)->change();
            $table->decimal('weight_kg', 5, 2)->nullable(false)->change();
            $table->string('ns_bmi_for_age')->nullable(false)->change();
            $table->string('ns_height_for_age')->nullable(false)->change();
            $table->string('immunization_kind')->nullable(false)->change();
            $table->string('menarche')->nullable(false)->change();
            $table->string('temperature')->nullable(false)->change();
            $table->string('blood_pressure')->nullable(false)->change();
            $table->string('pulse_rate')->nullable(false)->change();
            $table->string('respiratory_rate')->nullable(false)->change();
            $table->char('vision_l', 1)->nullable(false)->change();
            $table->char('vision_r', 1)->nullable(false)->change();
            $table->char('auditory_l', 1)->nullable(false)->change();
            $table->char('auditory_r', 1)->nullable(false)->change();
            $table->string('skin_scalp')->nullable(false)->change();
            $table->string('eyes_ears_nose')->nullable(false)->change();
            $table->string('mouth_neck_throat')->nullable(false)->change();
            $table->string('lungs_heart')->nullable(false)->change();
            $table->string('abdomen')->nullable(false)->change();
            $table->string('deformities')->nullable(false)->change();
            $table->text('others_specify')->nullable(false)->change();
        });
    }
};
