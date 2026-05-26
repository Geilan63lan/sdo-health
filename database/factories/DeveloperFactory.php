<?php

namespace Database\Factories;

use App\Models\Developer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Developer>
 */
class DeveloperFactory extends Factory
{
    protected $model = Developer::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'title' => fake()->jobTitle(),
            'bio' => fake()->paragraph(),
            'github_url' => 'https://github.com/'.fake()->userName(),
            'linkedin_url' => 'https://linkedin.com/in/'.fake()->userName(),
            'portfolio_url' => fake()->url(),
            'quote' => fake()->sentence(),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
