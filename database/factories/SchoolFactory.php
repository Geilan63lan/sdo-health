<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    protected $model = School::class;

    public function definition(): array
    {
        $legazpiBarangays = [
            'Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5',
            'Barangay 6', 'Barangay 7', 'Barangay 8', 'Barangay 9', 'Barangay 10',
            'Barangay 11', 'Barangay 12', 'Barangay 13', 'Barangay 14', 'Barangay 15',
            'Barangay 16', 'Barangay 17', 'Barangay 18', 'Barangay 19', 'Barangay 20',
            'Barangay 21', 'Barangay 22', 'Barangay 23', 'Barangay 24', 'Barangay 25',
            'Barangay 26', 'Barangay 27', 'Barangay 28', 'Barangay 29', 'Barangay 30',
            'Barangay 31', 'Barangay 32', 'Barangay 33', 'Barangay 34', 'Barangay 35',
            'Barangay 36', 'Barangay 37', 'Barangay 38', 'Barangay 39', 'Barangay 40',
            'Barangay 41', 'Barangay 42', 'Barangay 43', 'Barangay 44', 'Barangay 45',
            'Barangay 46', 'Barangay 47', 'Barangay 48', 'Barangay 49', 'Barangay 50',
            'Barangay 51', 'Barangay 52', 'Barangay 53', 'Barangay 54', 'Barangay 55',
            'Barangay 56', 'Barangay 57', 'Barangay 58', 'Barangay 59', 'Barangay 60',
            'Barangay 61', 'Barangay 62', 'Barangay 63', 'Barangay 64', 'Barangay 65',
            'Barangay 66', 'Barangay 67', 'Barangay 68', 'Barangay 69', 'Barangay 70',
            'Barangay 71', 'Barangay 72', 'Barangay 73', 'Barangay 74', 'Barangay 75',
            'Barangay 76', 'Barangay 77', 'Barangay 78', 'Barangay 79', 'Barangay 80',
            'Barangay 81', 'Barangay 82', 'Barangay 83', 'Barangay 84', 'Barangay 85',
            'Barangay 86', 'Barangay 87', 'Barangay 88', 'Barangay 89', 'Barangay 90',
            'Barangay 91', 'Barangay 92', 'Barangay 93', 'Barangay 94', 'Barangay 95',
            'Barangay 96', 'Barangay 97', 'Barangay 98', 'Barangay 99', 'Barangay 100',
            'Barangay Bogtong', 'Barangay Buang', 'Barangay Buyuan', 'Barangay Cagsiay',
            'Barangay Comun', 'Barangay Danao', 'Barangay Liwacsa', 'Barangay Maslog',
            'Barangay Pawa', 'Barangay Pili', 'Barangay San Francisco', 'Barangay San Roque',
            'Barangay Taysan', 'Barangay Tumpa', 'Barangay Imperial Court Subdivision',
            'Barangay Emerald Heights', 'Barangay East Government Center', 'Barangay West CBD',
        ];

        $types = ['Elementary School', 'National High School', 'Integrated School', 'Science High School', 'Child Development Center'];

        return [
            'school_id' => fake()->unique()->numerify('SCH-#####'),
            'name' => 'Legazpi City '.fake()->randomElement($types).' '.fake()->unique()->numberBetween(1, 20),
            'address' => fake()->randomElement($legazpiBarangays).', Legazpi City, Albay',
            'contact_number' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'principal_name' => fake()->name(),
            'is_active' => true,
            'category' => fake()->randomElement(['elementary', 'junior_high', 'senior_high', 'other']),
        ];
    }
}
