<?php

namespace App\Livewire;

use App\Enums\GradeLevel;
use App\Helpers\HealthLegend;
use App\Models\HealthExamination;
use App\Models\Student;
use Livewire\Component;

class HealthExaminationMatrix extends Component
{
    public int $studentId = 0;

    public string $studentName = '';

    public ?string $studentGradeLevel = null;

    public bool $showAll = false;

    public array $data = [];

    public int $selectedGradeIndex = -1;   // set from blade before saveGrade()

    protected ?Student $student = null;

    public function mount(int|Student|null $record = null): void
    {
        if ($record instanceof Student) {
            $this->student = $record;
        } elseif (is_int($record)) {
            $this->student = Student::find($record);
        } elseif ($record !== null) {
            $this->student = Student::find($record);
        }

        if (! $this->student) {
            return;
        }

        $this->studentId = $this->student->id;
        $this->studentName = $this->student->full_name;
        $this->studentGradeLevel = $this->student->current_grade_level;
        $this->loadData();
    }

    public function getStudent(): ?Student
    {
        return $this->student ??= Student::find($this->studentId);
    }

    public function loadData(): void
    {
        $student = $this->getStudent();

        $exams = HealthExamination::where('student_id', $student->id)
            ->get()
            ->keyBy('grade_level');

        foreach (GradeLevel::ordered() as $grade) {
            $exam = $exams[$grade] ?? null;
            $this->data[$grade] = [
                'id' => $exam?->id,
                'date_of_examination' => $exam?->date_of_examination?->format('Y-m-d') ?? '',
                'height_cm' => $exam?->height_cm ?? '',
                'weight_kg' => $exam?->weight_kg ?? '',
                'ns_bmi_for_age' => $exam?->ns_bmi_for_age ?? '',
                'ns_height_for_age' => $exam?->ns_height_for_age ?? '',
                'is_4ps_beneficiary' => $exam?->is_4ps_beneficiary ?? false,
                'is_sbfp_beneficiary' => $exam?->is_sbfp_beneficiary ?? false,
                'deworming_july' => $exam?->deworming_july ?? false,
                'deworming_january' => $exam?->deworming_january ?? false,
                'iron_supplementation' => $exam?->iron_supplementation ?? false,
                'immunization_kind' => $exam?->immunization_kind ?? '',
                'menarche' => $exam?->menarche ?? '',
                'temperature' => $exam?->temperature ?? '',
                'blood_pressure' => $exam?->blood_pressure ?? '',
                'pulse_rate' => $exam?->pulse_rate ?? '',
                'respiratory_rate' => $exam?->respiratory_rate ?? '',
                'vision_l' => $exam?->vision_l ?? '',
                'vision_r' => $exam?->vision_r ?? '',
                'auditory_l' => $exam?->auditory_l ?? '',
                'auditory_r' => $exam?->auditory_r ?? '',
                'skin_scalp' => $exam?->skin_scalp ?? '',
                'eyes_ears_nose' => $exam?->eyes_ears_nose ?? '',
                'mouth_neck_throat' => $exam?->mouth_neck_throat ?? '',
                'lungs_heart' => $exam?->lungs_heart ?? '',
                'abdomen' => $exam?->abdomen ?? '',
                'deformities' => $exam?->deformities ?? '',
                'others_specify' => $exam?->others_specify ?? '',
            ];
        }
    }

    public function save(string $grade): void
    {
        $grades = GradeLevel::ordered();
        $index = array_search($grade, $grades);

        if ($index === false) {
            return;
        }

        $this->selectedGradeIndex = $index;
        $this->performSave();
    }

    /**
     * No arguments — reads $this->selectedGradeIndex set from the blade.
     * Avoids Livewire argument-parsing issues with spaces in grade names.
     */
    public function performSave(): void
    {
        $grades = GradeLevel::ordered();

        if (! array_key_exists($this->selectedGradeIndex, $grades)) {
            return;
        }

        $grade = $grades[$this->selectedGradeIndex];
        $student = $this->getStudent();

        if (! $student) {
            return;
        }

        $gradeData = $this->data[$grade] ?? [];

        $boolFields = ['is_4ps_beneficiary', 'is_sbfp_beneficiary', 'deworming_july', 'deworming_january', 'iron_supplementation'];
        $floatFields = ['height_cm', 'weight_kg'];

        $fillable = [
            'date_of_examination', 'height_cm', 'weight_kg',
            'ns_bmi_for_age', 'ns_height_for_age',
            'is_4ps_beneficiary', 'is_sbfp_beneficiary',
            'deworming_july', 'deworming_january', 'iron_supplementation',
            'immunization_kind', 'menarche',
            'temperature', 'blood_pressure', 'pulse_rate', 'respiratory_rate',
            'vision_l', 'vision_r', 'auditory_l', 'auditory_r',
            'skin_scalp', 'eyes_ears_nose', 'mouth_neck_throat',
            'lungs_heart', 'abdomen', 'deformities', 'others_specify',
        ];

        $updateData = [];
        foreach ($fillable as $field) {
            if (! array_key_exists($field, $gradeData)) {
                continue;
            }
            $value = $gradeData[$field];
            if (in_array($field, $floatFields)) {
                $value = $value === '' ? null : (float) $value;
            }
            if (in_array($field, $boolFields)) {
                $value = (bool) $value;
            }
            $updateData[$field] = $value;
        }

        $record = HealthExamination::updateOrCreate(
            ['student_id' => $student->id, 'grade_level' => $grade],
            array_merge($updateData, ['examined_by' => auth()->id()])
        );

        $this->data[$grade]['id'] = $record->id;

        $this->dispatch('saved', grade: $grade);
    }

    public function toggleShowAll(): void
    {
        $this->showAll = ! $this->showAll;
    }

    public function isVisible(string $grade): bool
    {
        if ($this->showAll || ! $this->studentGradeLevel) {
            return true;
        }

        return GradeLevel::indexOf($grade) <= GradeLevel::indexOf($this->studentGradeLevel);
    }

    public function getLegendOptions(): array
    {
        return [
            'ns_bmi' => HealthLegend::options('ns_bmi'),
            'ns_height' => HealthLegend::options('ns_height'),
            'screenings' => HealthLegend::options('screenings'),
            'skin_scalp' => HealthLegend::options('skin_scalp'),
            'eyes_ears_nose' => HealthLegend::options('eyes_ears_nose'),
            'mouth_neck_throat' => HealthLegend::options('mouth_neck_throat'),
            'lungs_heart' => HealthLegend::options('lungs_heart'),
            'abdomen' => HealthLegend::options('abdomen'),
            'deformities' => HealthLegend::options('deformities'),
        ];
    }

    public function render()
    {
        return view('livewire.health-examination-matrix', [
            'gradeLevels' => GradeLevel::ordered(),
            'legends' => $this->getLegendOptions(),
        ]);
    }
}
