<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\SchoolHealthCoordinator;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolHealthCoordinatorFactory extends Factory
{
    protected $model = SchoolHealthCoordinator::class;

    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'position' => fake()->randomElement([
                'School Health Coordinator',
                'Head School Nurse',
                'Health Program Manager',
                'School Nurse Coordinator',
            ]),
            'is_active' => true,
        ];
    }
}
