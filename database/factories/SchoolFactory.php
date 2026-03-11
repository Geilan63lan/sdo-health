<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    protected $model = School::class;

    public function definition(): array
    {
        $locations = ['Manila', 'Quezon City', 'Cebu', 'Davao', 'Iloilo', 'Bacolod', 'Pampanga', 'Bulacan', 'Laguna', 'Batangas', 'Cavite', 'Rizal', 'Pangasinan', 'Tarlac', 'Zambales'];
        $types = ['Elementary School', 'National High School', 'Integrated School', 'Science High School'];
        
        return [
            'name' => fake()->randomElement($locations) . ' ' . fake()->unique()->numberBetween(1, 1000) . ' ' . fake()->randomElement($types),
            'address' => fake()->address(),
            'level' => fake()->randomElement(['elementary', 'jhs', 'shs', 'integrated']),
            'contact_number' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'principal_name' => fake()->name(),
            'is_active' => true,
        ];
    }
}
