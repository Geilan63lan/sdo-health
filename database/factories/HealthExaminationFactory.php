<?php

namespace Database\Factories;

use App\Models\HealthExamination;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HealthExaminationFactory extends Factory
{
    protected $model = HealthExamination::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'examined_by' => User::factory(),
            'grade_level' => fake()->randomElement(['Kinder', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12']),
            'date_of_examination' => fake()->date(),
            'designation' => fake()->optional()->jobTitle(),
            'height_cm' => fake()->randomFloat(2, 100, 180),
            'weight_kg' => fake()->randomFloat(2, 20, 80),
            'ns_bmi_for_age' => fake()->randomElement(['a', 'c', 'd', 'e']),
            'ns_height_for_age' => fake()->randomElement(['f', 'g', 'h', 'i']),
            'is_4ps_beneficiary' => fake()->boolean(),
            'is_sbfp_beneficiary' => fake()->boolean(),
            'deworming_july' => fake()->boolean(),
            'deworming_january' => fake()->boolean(),
            'iron_supplementation' => fake()->boolean(),
            'immunization_kind' => fake()->optional()->randomElement(['BCG', 'DPT', 'OPV', 'Measles', 'Hepatitis B']),
            'menarche' => fake()->optional()->randomElement(['N/A', '12 years old', '13 years old']),
            'temperature' => fake()->optional()->randomElement(['36.5°C', '37.0°C', '37.5°C']),
            'blood_pressure' => fake()->optional()->randomElement(['90/60', '100/70', '110/70', '120/80']),
            'pulse_rate' => fake()->optional()->randomElement(['70', '80', '90', '100']),
            'respiratory_rate' => fake()->optional()->randomElement(['16', '18', '20', '22']),
            'vision_l' => fake()->optional()->randomElement(['a', 'b']),
            'vision_r' => fake()->optional()->randomElement(['a', 'b']),
            'auditory_l' => fake()->optional()->randomElement(['a', 'b']),
            'auditory_r' => fake()->optional()->randomElement(['a', 'b']),
            'skin_scalp' => fake()->optional()->randomElement(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm']),
            'eyes_ears_nose' => fake()->optional()->randomElement(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l']),
            'mouth_neck_throat' => fake()->optional()->randomElement(['a', 'b', 'c', 'd', 'e', 'f']),
            'lungs_heart' => fake()->optional()->randomElement(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h']),
            'abdomen' => fake()->optional()->randomElement(['a', 'b', 'c', 'd', 'e', 'f']),
            'deformities' => fake()->optional()->randomElement(['a', 'b']),
            'others_specify' => fake()->optional()->sentence(),
        ];
    }
}
