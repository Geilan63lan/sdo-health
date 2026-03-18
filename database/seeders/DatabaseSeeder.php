<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use App\Models\Student;
use App\Models\HealthRecord;
use App\Models\HealthProgram;
use App\Models\Vaccination;
use App\Models\Absence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed all schools first
        $this->call(SchoolSeeder::class);

        $filipinoLastNames = ['Garcia', 'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Dela Cruz', 'Mendoza', 'Pascual', 'Castillo', 'Villanueva', 'Gonzales', 'Rivera', 'Aquino', 'Santiago'];
        $filipinoFirstNames = ['Jose', 'Maria', 'Juan', 'Angelo', 'Liza', 'Rene', 'Teresa', 'Antonio', 'Cristina', 'Ricardo', 'Elena', 'Roberto', 'Carmen', 'Francisco', 'Pilar'];

        // 2. Create SDO Admin
        User::factory()->create([
            'name' => 'SDO Admin',
            'email' => 'admin@sdo.gov.ph',
            'password' => Hash::make('password'),
            'role' => 'sdo_admin',
        ]);

        // 3. Get all schools from the seeder
        $schools = School::all();

        foreach ($schools as $school) {
            // Create Principal for the school
            User::factory()->create([
                'name' => "Principal " . fake()->randomElement($filipinoFirstNames) . " " . fake()->randomElement($filipinoLastNames),
                'email' => "principal." . $school->id . "@example.com",
                'role' => 'principal',
                'school_id' => $school->id,
            ]);

            // Create Health Coordinator for the school
            $coordinator = User::factory()->create([
                'name' => "Nurse " . fake()->randomElement($filipinoFirstNames) . " " . fake()->randomElement($filipinoLastNames),
                'email' => "nurse." . $school->id . "@example.com",
                'role' => 'health_coordinator',
                'school_id' => $school->id,
            ]);

            // 3. Create Students and their records
            Student::factory(20)->create([
                'school_id' => $school->id,
                'first_name' => fn() => fake()->randomElement($filipinoFirstNames),
                'last_name' => fn() => fake()->randomElement($filipinoLastNames),
                'guardian_name' => fn() => fake()->randomElement($filipinoFirstNames) . " " . fake()->randomElement($filipinoLastNames),
            ])->each(function ($student) use ($coordinator) {
                // Add Health Records
                HealthRecord::factory(rand(1, 3))->create([
                    'student_id' => $student->id,
                    'recorded_by' => $coordinator->id,
                ]);

                // Add Vaccinations
                Vaccination::factory(rand(1, 4))->create([
                    'student_id' => $student->id,
                    'recorded_by' => $coordinator->id,
                    'vaccine_name' => fake()->randomElement(['BCG', 'Hepatitis B', 'DTP', 'Polio', 'MMR', 'COVID-19']),
                    'date_given' => fake()->dateTimeBetween('-2 years', 'now'),
                ]);

                // Add some Absences
                if (rand(1, 10) > 7) {
                    Absence::factory(rand(1, 2))->create([
                        'student_id' => $student->id,
                        'recorded_by' => $coordinator->id,
                        'is_health_related' => true,
                        'absence_date' => fake()->dateTimeBetween('-6 months', 'now'),
                    ]);
                }
            });

            // 4. Create Health Programs for the school
            HealthProgram::factory(3)->create([
                'school_id' => $school->id,
                'coordinator_id' => $coordinator->id,
                'status' => fake()->randomElement(['planned', 'ongoing', 'completed', 'cancelled']),
                'type' => fake()->randomElement(['screening', 'vaccination', 'education', 'counseling', 'other']),
            ]);
        }
    }
}
