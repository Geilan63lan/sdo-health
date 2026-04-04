<?php

use App\Enums\GradeLevel;
use App\Helpers\HealthLegend;
use App\Livewire\HealthExaminationMatrix;
use App\Models\HealthExamination;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ── GradeLevel Enum Tests ──

test('GradeLevel ordered returns all 13 grades', function () {
    $ordered = GradeLevel::ordered();

    expect($ordered)->toHaveCount(13)
        ->toBe([
            'Kinder', 'Grade 1', 'Grade 2', 'Grade 3',
            'Grade 4', 'Grade 5', 'Grade 6', 'Grade 7',
            'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12',
        ]);
});

test('GradeLevel indexOf returns correct position', function () {
    expect(GradeLevel::indexOf('Kinder'))->toBe(0);
    expect(GradeLevel::indexOf('Grade 1'))->toBe(1);
    expect(GradeLevel::indexOf('Grade 6'))->toBe(6);
    expect(GradeLevel::indexOf('Grade 12'))->toBe(12);
    expect(GradeLevel::indexOf('Invalid'))->toBe(false);
});

test('GradeLevel next returns following grade', function () {
    expect(GradeLevel::next('Kinder'))->toBe('Grade 1');
    expect(GradeLevel::next('Grade 5'))->toBe('Grade 6');
    expect(GradeLevel::next('Grade 11'))->toBe('Grade 12');
});

test('GradeLevel next returns null for last grade', function () {
    expect(GradeLevel::next('Grade 12'))->toBeNull();
});

test('GradeLevel next returns null for invalid grade', function () {
    expect(GradeLevel::next('Invalid'))->toBeNull();
});

test('GradeLevel upTo returns grades up to and including given grade', function () {
    expect(GradeLevel::upTo('Kinder'))->toBe(['Kinder']);
    expect(GradeLevel::upTo('Grade 3'))->toBe(['Kinder', 'Grade 1', 'Grade 2', 'Grade 3']);
    expect(GradeLevel::upTo('Grade 12'))->toBe(GradeLevel::ordered());
});

test('GradeLevel asSelectOptions returns key-value array', function () {
    $options = GradeLevel::asSelectOptions();

    expect($options)->toHaveCount(13)
        ->toHaveKey('Kinder', 'Kinder')
        ->toHaveKey('Grade 1', 'Grade 1')
        ->toHaveKey('Grade 12', 'Grade 12');
});

// ── HealthLegend Options Tests ──

test('HealthLegend options returns formatted select options', function () {
    $options = HealthLegend::options('skin_scalp');

    expect($options)->toHaveKey('a')
        ->toHaveKey('')
        ->and($options[''])->toBe('—')
        ->and($options['a'])->toBe('a: Normal')
        ->and($options['b'])->toBe('b: Presence of Lice');
});

test('HealthLegend options returns empty fallback for unknown category', function () {
    $options = HealthLegend::options('nonexistent');

    expect($options)->toBe(['' => '—']);
});

// ── HealthExaminationMatrix Visibility Tests (unit-level) ──

test('column visibility filtered by student current grade level', function () {
    $student = Student::factory()->create(['current_grade_level' => 'Grade 1']);

    $component = new HealthExaminationMatrix;
    $component->mount($student);

    expect($component->isVisible('Kinder'))->toBeTrue();
    expect($component->isVisible('Grade 1'))->toBeTrue();
    expect($component->isVisible('Grade 2'))->toBeFalse();
    expect($component->isVisible('Grade 12'))->toBeFalse();
});

test('all columns visible when student has no current grade level', function () {
    $student = Student::factory()->create(['current_grade_level' => null]);

    $component = new HealthExaminationMatrix;
    $component->mount($student);

    foreach (GradeLevel::ordered() as $grade) {
        expect($component->isVisible($grade))->toBeTrue();
    }
});

test('toggle show all reveals all columns', function () {
    $student = Student::factory()->create(['current_grade_level' => 'Grade 1']);

    $component = new HealthExaminationMatrix;
    $component->mount($student);

    expect($component->isVisible('Grade 12'))->toBeFalse();

    $component->toggleShowAll();

    expect($component->showAll)->toBeTrue();
    expect($component->isVisible('Grade 12'))->toBeTrue();

    $component->toggleShowAll();

    expect($component->showAll)->toBeFalse();
    expect($component->isVisible('Grade 12'))->toBeFalse();
});

// ── HealthExaminationMatrix Data Tests (unit-level) ──

test('loadData populates existing exam data', function () {
    $student = Student::factory()->create(['current_grade_level' => 'Grade 3']);
    $exam = HealthExamination::factory()->create([
        'student_id' => $student->id,
        'grade_level' => 'Kinder',
        'height_cm' => 110.5,
        'weight_kg' => 20.0,
    ]);

    $component = new HealthExaminationMatrix;
    $component->mount($student);

    expect($component->data['Kinder']['height_cm'])->toBe('110.50')
        ->and($component->data['Kinder']['weight_kg'])->toBe('20.00')
        ->and($component->data['Grade 2']['height_cm'])->toBe('');
});

test('save creates new examination record for empty grade', function () {
    $user = User::factory()->create();
    $student = Student::factory()->create(['current_grade_level' => 'Grade 3']);
    $this->actingAs($user);

    $component = new HealthExaminationMatrix;
    $component->mount($student);

    $component->data['Grade 2']['date_of_examination'] = '2025-06-15';
    $component->data['Grade 2']['height_cm'] = '115.5';
    $component->data['Grade 2']['weight_kg'] = '22.3';
    $component->data['Grade 2']['ns_bmi_for_age'] = 'a';
    $component->data['Grade 2']['ns_height_for_age'] = 'f';

    $component->save('Grade 2');

    $this->assertDatabaseHas('health_examinations', [
        'student_id' => $student->id,
        'grade_level' => 'Grade 2',
        'height_cm' => 115.5,
        'weight_kg' => 22.3,
        'ns_bmi_for_age' => 'a',
    ]);
});

test('save updates existing examination record', function () {
    $user = User::factory()->create();
    $student = Student::factory()->create(['current_grade_level' => 'Grade 5']);
    $this->actingAs($user);
    $exam = HealthExamination::factory()->create([
        'student_id' => $student->id,
        'grade_level' => 'Grade 3',
        'height_cm' => 120.0,
        'weight_kg' => 25.0,
    ]);

    $component = new HealthExaminationMatrix;
    $component->mount($student);

    $component->data['Grade 3']['height_cm'] = '125.0';
    $component->data['Grade 3']['weight_kg'] = '27.5';
    $component->save('Grade 3');

    $this->assertDatabaseHas('health_examinations', [
        'id' => $exam->id,
        'height_cm' => 125.0,
        'weight_kg' => 27.5,
    ]);

    $this->assertDatabaseCount('health_examinations', 1);
});

test('save handles boolean intervention fields', function () {
    $user = User::factory()->create();
    $student = Student::factory()->create(['current_grade_level' => 'Grade 4']);
    $this->actingAs($user);

    $component = new HealthExaminationMatrix;
    $component->mount($student);

    $component->data['Kinder']['is_4ps_beneficiary'] = true;
    $component->data['Kinder']['deworming_july'] = true;
    $component->data['Kinder']['deworming_january'] = false;
    $component->data['Kinder']['iron_supplementation'] = true;
    $component->save('Kinder');

    $this->assertDatabaseHas('health_examinations', [
        'student_id' => $student->id,
        'grade_level' => 'Kinder',
        'is_4ps_beneficiary' => true,
        'deworming_july' => true,
        'deworming_january' => false,
        'iron_supplementation' => true,
    ]);
});

test('render returns correct view with data', function () {
    $student = Student::factory()->create(['current_grade_level' => 'Kinder']);

    $component = new HealthExaminationMatrix;
    $component->mount($student);

    $view = $component->render();

    expect($view->getName())->toBe('livewire.health-examination-matrix');
});
