<?php

namespace Database\Factories;

use App\Models\HealthRecord;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HealthRecordFactory extends Factory
{
    protected $model = HealthRecord::class;

    public function definition(): array
    {
        $height = fake()->randomFloat(2, 100, 180); // cm
        $weight = fake()->randomFloat(2, 20, 90);   // kg
        $bmi = $weight / (($height / 100) ** 2);
        
        $category = match(true) {
            $bmi < 18.5 => 'Underweight',
            $bmi < 25 => 'Normal',
            $bmi < 30 => 'Overweight',
            default => 'Obese',
        };

        return [
            'student_id' => Student::factory(),
            'record_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'height_cm' => $height,
            'weight_kg' => $weight,
            'bmi' => round($bmi, 1),
            'bmi_category' => $category,
            'medical_conditions' => fake()->optional(0.2)->sentence(),
            'allergies' => fake()->optional(0.2)->words(3, true),
            'medications' => fake()->optional(0.1)->sentence(),
            'notes' => fake()->optional(0.3)->paragraph(),
            'recorded_by' => User::factory(),
        ];
    }
}
