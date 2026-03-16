<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use App\Models\Vaccination;
use Illuminate\Database\Eloquent\Factories\Factory;

class VaccinationFactory extends Factory
{
    protected $model = Vaccination::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'vaccine_name' => fake()->randomElement(['BCG', 'Hepatitis B', 'DTP', 'Polio', 'MMR', 'COVID-19', 'HPV']),
            'date_given' => fake()->dateTimeBetween('-2 years', 'now'),
            'dose_number' => fake()->randomElement(['1st Dose', '2nd Dose', 'Booster']),
            'administered_by' => 'Dr. '.fake()->name(),
            'batch_number' => fake()->bothify('BATCH-####??'),
            'notes' => fake()->optional()->sentence(),
            'recorded_by' => User::factory(),
        ];
    }
}
