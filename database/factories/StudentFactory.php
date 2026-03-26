<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'lrn' => fake()->unique()->numerify('############'), // 12 digits
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->lastName(),
            'last_name' => fake()->lastName(),
            'suffix' => fake()->optional(0.1)->suffix(),
            'birth_date' => fake()->date('Y-m-d', '-5 years'),
            'sex' => fake()->randomElement(['male', 'female']),
            'address' => fake()->address(),
            'guardian_name' => fake()->name(),
            'guardian_contact' => fake()->phoneNumber(),
            'guardian_relationship' => fake()->randomElement(['Father', 'Mother', 'Grandparent', 'Aunt', 'Uncle']),
            'is_active' => true,
            'current_grade_level' => fake()->randomElement([
                'Kinder', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5',
                'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12',
            ]),
        ];
    }
}
