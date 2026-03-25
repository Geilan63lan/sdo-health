<?php

use App\Models\HealthExamination;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('sdo admin can view all health examinations', function () {
    $sdoAdmin = User::factory()->create();
    $sdoAdmin->assignRole('sdo_admin');

    $school = School::factory()->create();
    $student = Student::factory()->create(['school_id' => $school->id]);
    $examination = HealthExamination::factory()->create(['student_id' => $student->id]);

    $this->actingAs($sdoAdmin);
    $this->assertTrue($sdoAdmin->can('viewAny', HealthExamination::class));
    $this->assertTrue($sdoAdmin->can('view', $examination));
});

test('health coordinator can create health examination', function () {
    $school = School::factory()->create();
    $coordinator = User::factory()->create(['school_id' => $school->id]);
    $coordinator->assignRole('health_coordinator');

    $this->actingAs($coordinator);
    $this->assertTrue($coordinator->can('create', HealthExamination::class));
});

test('health examination has correct relationships', function () {
    $school = School::factory()->create();
    $student = Student::factory()->create(['school_id' => $school->id]);
    $examiner = User::factory()->create();

    $examination = HealthExamination::factory()->create([
        'student_id' => $student->id,
        'examined_by' => $examiner->id,
    ]);

    expect($examination->student->id)->toBe($student->id);
    expect($examination->examinedBy->id)->toBe($examiner->id);
});

test('student can access health examinations via relationship', function () {
    $school = School::factory()->create();
    $student = Student::factory()->create(['school_id' => $school->id]);

    $examination1 = HealthExamination::factory()->create(['student_id' => $student->id]);
    $examination2 = HealthExamination::factory()->create(['student_id' => $student->id]);

    expect($student->healthExaminations)->toHaveCount(2);
    expect($student->healthExaminations->pluck('id'))->toContain($examination1->id, $examination2->id);
});

test('health examination stores boolean interventions correctly', function () {
    $examination = HealthExamination::factory()->create([
        'is_4ps_beneficiary' => true,
        'is_sbfp_beneficiary' => false,
        'deworming_july' => true,
        'deworming_january' => false,
        'iron_supplementation' => true,
    ]);

    expect($examination->is_4ps_beneficiary)->toBeTrue();
    expect($examination->is_sbfp_beneficiary)->toBeFalse();
    expect($examination->deworming_july)->toBeTrue();
    expect($examination->deworming_january)->toBeFalse();
    expect($examination->iron_supplementation)->toBeTrue();
});

test('health examination stores legend codes correctly', function () {
    $examination = HealthExamination::factory()->create([
        'ns_bmi_for_age' => 'd',
        'ns_height_for_age' => 'h',
        'vision_l' => 'a',
        'vision_r' => 'b',
        'skin_scalp' => 'b',
        'eyes_ears_nose' => 'a',
    ]);

    expect($examination->ns_bmi_for_age)->toBe('d');
    expect($examination->ns_height_for_age)->toBe('h');
    expect($examination->vision_l)->toBe('a');
    expect($examination->vision_r)->toBe('b');
    expect($examination->skin_scalp)->toBe('b');
    expect($examination->eyes_ears_nose)->toBe('a');
});
