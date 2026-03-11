<?php

namespace Database\Factories;

use App\Models\HealthProgram;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HealthProgramFactory extends Factory
{
    protected $model = HealthProgram::class;

    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'name' => fake()->randomElement(['Anti-Dengue Campaign', 'Deworming Session', 'Annual Physical Exam', 'Handwashing Workshop', 'Mental Health Seminar']),
            'description' => fake()->paragraph(),
            'type' => fake()->randomElement(['screening', 'vaccination', 'education', 'counseling', 'other']),
            'start_date' => fake()->dateTimeBetween('now', '+1 month'),
            'end_date' => fake()->dateTimeBetween('+1 month', '+2 months'),
            'target_grade' => fake()->randomElement(['Kinder', 'Grade 1', 'Grade 7', 'Grade 11', 'All']),
            'status' => fake()->randomElement(['planned', 'ongoing', 'completed', 'cancelled']),
            'coordinator_id' => User::factory(),
        ];
    }
}
