<?php

namespace Database\Factories;

use App\Models\Absence;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsenceFactory extends Factory
{
    protected $model = Absence::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'absence_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'reason' => fake()->randomElement(['Fever', 'Cough', 'Stomach ache', 'Dental issue', 'Injury', 'Other']),
            'diagnosis' => fake()->optional(0.5)->sentence(),
            'is_health_related' => true,
            'days_absent' => fake()->numberBetween(1, 5),
            'notes' => fake()->optional()->paragraph(),
            'recorded_by' => User::factory(),
        ];
    }
}
