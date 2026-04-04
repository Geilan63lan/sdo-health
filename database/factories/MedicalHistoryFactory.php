<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalHistory>
 */
class MedicalHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hasAllergies = fake()->boolean(20); // 20% chance
        $hasMedicalConditions = fake()->boolean(15); // 15% chance
        $hasPastSurgery = fake()->boolean(10); // 10% chance
        $hasFamilyHistory = fake()->boolean(30); // 30% chance

        return [
            'student_id' => Student::factory(),

            // Allergies
            'has_allergies' => $hasAllergies,
            'allergy_types' => $hasAllergies ? fake()->randomElements(['medicine', 'food', 'dust', 'pollen'], rand(1, 2)) : null,
            'allergy_others' => $hasAllergies && fake()->boolean(30) ? 'Peanuts' : null,

            // Ongoing Medical Conditions
            'has_medical_conditions' => $hasMedicalConditions,
            'condition_types' => $hasMedicalConditions ? fake()->randomElements(['asthma', 'seizure', 'diabetes', 'heart disease'], rand(1, 2)) : null,
            'condition_others' => $hasMedicalConditions && fake()->boolean(30) ? 'Mild Scoliosis' : null,

            // Surgery / Hospitalization
            'has_past_surgery' => $hasPastSurgery,
            'surgery_details' => $hasPastSurgery ? 'Appendectomy in '.fake()->year() : null,

            // Family History
            'family_history' => $hasFamilyHistory ? fake()->randomElements(['hypertension', 'diabetes', 'cancer', 'asthma'], rand(1, 2)) : null,
            'cancer_type' => $hasFamilyHistory && fake()->boolean(20) ? 'Breast Cancer' : null,
            'family_history_other' => null,

            // Lifestyle & Physical
            'smoke_exposure' => fake()->boolean(20),
            'dominant_hand' => fake()->randomElement(['right', 'left', 'both']),
        ];
    }
}
