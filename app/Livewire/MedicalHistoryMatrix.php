<?php

namespace App\Livewire;

use App\Enums\GradeLevel;
use App\Models\MedicalHistoryItem;
use App\Models\Student;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Component;

class MedicalHistoryMatrix extends Component
{
    public int $studentId;

    public string $studentName = '';

    public ?string $studentGradeLevel = null;

    public bool $showAll = false;

    public int $hiddenCount = 0;

    public array $data = [];

    public bool $isModalOpen = false;

    public ?string $selectedGrade = null;

    public ?string $pendingValidationGrade = null;

    protected ?User $currentUser = null;

    protected ?Student $student = null;

    public function mount(int $studentId): void
    {
        $this->studentId = $studentId;

        $student = Student::findOrFail($studentId);

        $this->studentName = $student->full_name;
        $this->studentGradeLevel = $student->current_grade_level;

        $this->loadData();
    }

    public function getStudent(): Student
    {
        return $this->student ??= Student::findOrFail($this->studentId);
    }

    public function getCurrentUser(): User
    {
        return $this->currentUser ??= Filament::getCurrentPanel()?->auth()->user();
    }

    public function isAdmin(): bool
    {
        return $this->getCurrentUser()?->isAdmin() ?? false;
    }

    public function canSave(string $grade): bool
    {
        $item = $this->getItemForGrade($grade);
        if (! $item) {
            return true;
        }
        if ($item->validated && ! $this->isAdmin()) {
            return false;
        }

        return true;
    }

    public function canEdit(string $grade): bool
    {
        $item = $this->getItemForGrade($grade);
        if (! $item) {
            return true;
        }

        return $item->canEdit($this->getCurrentUser());
    }

    public function isValidated(string $grade): bool
    {
        $item = $this->getItemForGrade($grade);

        return $item?->isValidated() ?? false;
    }

    public function getItemForGrade(string $grade): ?MedicalHistoryItem
    {
        return MedicalHistoryItem::where('student_id', $this->studentId)
            ->where('grade_level', $grade)
            ->first();
    }

    public function openModal(string $grade): void
    {
        $this->selectedGrade = $grade;
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->selectedGrade = null;
    }

    public function performSaveByGrade(string $grade): void
    {
        $grades = GradeLevel::ordered();
        $gradeIndex = array_search($grade, $grades);
        if ($gradeIndex === false) {
            return;
        }
        $this->performSave($gradeIndex);
    }

    public function validateEntry(): void
    {
        if (! $this->selectedGrade) {
            return;
        }

        $item = $this->getItemForGrade($this->selectedGrade);
        if ($item) {
            $item->validate($this->getCurrentUser());
            $this->loadData();
        }

        $this->closeModal();
    }

    public function invalidateEntry(): void
    {
        if (! $this->selectedGrade) {
            return;
        }

        $item = $this->getItemForGrade($this->selectedGrade);
        if ($item) {
            $item->invalidate($this->getCurrentUser());
            $this->loadData();
        }

        $this->closeModal();
    }

    public function setGradeForValidate(string $grade): void
    {
        $this->pendingValidationGrade = $grade;
    }

    public function confirmValidate(): void
    {
        if (! $this->pendingValidationGrade) {
            return;
        }
        $grade = $this->pendingValidationGrade;
        $item = $this->getItemForGrade($grade);
        if ($item && $item->id) {
            $item->validate($this->getCurrentUser());
            $this->loadData();
        }
        $this->pendingValidationGrade = null;
    }

    public function cancelValidate(): void
    {
        $this->pendingValidationGrade = null;
    }

    public function validateEntryForGrade(string $grade): void
    {
        $item = $this->getItemForGrade($grade);
        if ($item) {
            $item->validate($this->getCurrentUser());
            $this->loadData();
        }
    }

    public function invalidateEntryForGrade(string $grade): void
    {
        $item = $this->getItemForGrade($grade);
        if ($item) {
            $item->invalidate($this->getCurrentUser());
            $this->loadData();
        }
    }

    public function loadData(): void
    {
        $items = MedicalHistoryItem::where('student_id', $this->studentId)
            ->get()
            ->keyBy('grade_level');

        foreach (GradeLevel::ordered() as $grade) {
            $item = $items[$grade] ?? null;
            $allergyTypes = $item?->allergy_types ?? [];
            $conditionTypes = $item?->condition_types ?? [];
            $familyHistory = $item?->family_history ?? [];

            $this->data[$grade] = [
                'id' => $item?->id,
                'has_allergies' => ! empty($allergyTypes) || ($item?->allergy_others ?? '') !== '',
                'allergy_types' => $allergyTypes,
                'allergy_others' => $item?->allergy_others ?? '',
                'has_medical_conditions' => ! empty($conditionTypes) || ($item?->condition_others ?? '') !== '',
                'condition_types' => $conditionTypes,
                'condition_others' => $item?->condition_others ?? '',
                'has_past_surgery' => ($item?->surgery_details ?? '') !== '',
                'surgery_details' => $item?->surgery_details ?? '',
                'family_history' => $familyHistory,
                'cancer_type' => $item?->cancer_type ?? '',
                'family_history_other' => $item?->family_history_other ?? '',
                'smoke_exposure' => $item?->smoke_exposure ?? false,
                'dominant_hand' => $item?->dominant_hand ?? '',
                'validated' => $item?->validated ?? false,
                'validated_at' => $item?->validated_at instanceof \Carbon\Carbon ? $item->validated_at->format('Y-m-d H:i:s') : null,
                'invalidated_at' => $item?->invalidated_at instanceof \Carbon\Carbon ? $item->invalidated_at->format('Y-m-d H:i:s') : null,
            ];
        }
    }

    public function performSave(int $gradeIndex): void
    {
        $grades = GradeLevel::ordered();

        if (! array_key_exists($gradeIndex, $grades)) {
            return;
        }

        $grade = $grades[$gradeIndex];

        if (! $this->canSave($grade)) {
            return;
        }

        $gradeData = $this->data[$grade] ?? [];

        $boolFields = [
            'has_allergies',
            'has_medical_conditions',
            'has_past_surgery',
            'smoke_exposure',
        ];

        $fillable = [
            'has_allergies',
            'allergy_types',
            'allergy_others',
            'has_medical_conditions',
            'condition_types',
            'condition_others',
            'has_past_surgery',
            'surgery_details',
            'family_history',
            'cancer_type',
            'family_history_other',
            'smoke_exposure',
            'dominant_hand',
        ];

        $arrayFields = ['allergy_types', 'condition_types', 'family_history'];

        $textFields = [
            'allergy_others',
            'condition_others',
            'surgery_details',
            'family_history_other',
        ];

        $updateData = [];

        foreach ($fillable as $field) {
            if (! array_key_exists($field, $gradeData)) {
                continue;
            }
            $value = $gradeData[$field];

            if (in_array($field, $arrayFields) && is_array($value)) {
                $value = array_filter($value);
                $value = empty($value) ? null : $value;
            }

            if (in_array($field, $textFields)) {
                $value = $value === '' ? null : $value;
            }

            if (in_array($field, $boolFields)) {
                $value = (bool) $value;
            }

            $updateData[$field] = $value;
        }

        $record = MedicalHistoryItem::updateOrCreate(
            ['student_id' => $this->studentId, 'grade_level' => $grade],
            $updateData
        );

        $this->data[$grade]['id'] = $record->id;

        $this->dispatch('mhm-saved', grade: $grade);
    }

    public function toggleShowAll(): void
    {
        $this->showAll = ! $this->showAll;
        $this->loadData();
    }

    public function isVisible(string $grade): bool
    {
        if ($this->showAll) {
            return true;
        }

        return $grade === $this->studentGradeLevel;
    }

    public function toggleAllergyType(string $grade, string $type): void
    {
        if (! $this->canSave($grade)) {
            return;
        }

        $currentTypes = $this->data[$grade]['allergy_types'] ?? [];
        if (in_array($type, $currentTypes)) {
            $this->data[$grade]['allergy_types'] = array_filter($currentTypes, fn ($t) => $t !== $type);
        } else {
            $this->data[$grade]['allergy_types'][] = $type;
        }
        $this->data[$grade]['allergy_types'] = array_values($this->data[$grade]['allergy_types']);

        $this->data[$grade]['has_allergies'] = ! empty($this->data[$grade]['allergy_types']);
    }

    public function toggleConditionType(string $grade, string $type): void
    {
        if (! $this->canSave($grade)) {
            return;
        }

        $currentTypes = $this->data[$grade]['condition_types'] ?? [];
        if (in_array($type, $currentTypes)) {
            $this->data[$grade]['condition_types'] = array_filter($currentTypes, fn ($t) => $t !== $type);
        } else {
            $this->data[$grade]['condition_types'][] = $type;
        }
        $this->data[$grade]['condition_types'] = array_values($this->data[$grade]['condition_types']);

        $this->data[$grade]['has_medical_conditions'] = ! empty($this->data[$grade]['condition_types']);
    }

    public function toggleFamilyHistory(string $grade, string $type): void
    {
        if (! $this->canSave($grade)) {
            return;
        }

        $currentTypes = $this->data[$grade]['family_history'] ?? [];
        if (in_array($type, $currentTypes)) {
            $this->data[$grade]['family_history'] = array_filter($currentTypes, fn ($t) => $t !== $type);
        } else {
            $this->data[$grade]['family_history'][] = $type;
        }
        $this->data[$grade]['family_history'] = array_values($this->data[$grade]['family_history']);
    }

    public function setDominantHand(string $grade, string $hand): void
    {
        if (! $this->canSave($grade)) {
            return;
        }

        $this->data[$grade]['dominant_hand'] = $hand;
    }

    public function toggleAllergyBool(string $grade): void
    {
        if (! $this->canSave($grade)) {
            return;
        }

        $this->data[$grade]['has_allergies'] = ! ($this->data[$grade]['has_allergies'] ?? false);
    }

    public function toggleConditionBool(string $grade): void
    {
        if (! $this->canSave($grade)) {
            return;
        }

        $this->data[$grade]['has_medical_conditions'] = ! ($this->data[$grade]['has_medical_conditions'] ?? false);
    }

    public function toggleSurgeryBool(string $grade): void
    {
        if (! $this->canSave($grade)) {
            return;
        }

        $this->data[$grade]['has_past_surgery'] = ! ($this->data[$grade]['has_past_surgery'] ?? false);
    }

    public function render()
    {
        $gradeLevels = GradeLevel::ordered();
        $visibleGrades = array_filter($gradeLevels, fn ($g) => $this->isVisible($g));
        $this->hiddenCount = count($gradeLevels) - count($visibleGrades);
        $currentIdx = $this->studentGradeLevel ? array_search($this->studentGradeLevel, $gradeLevels) : 0;

        return view('livewire.medical-history-matrix', [
            'gradeLevels' => $gradeLevels,
            'hiddenCount' => $this->hiddenCount,
            'currentIdx' => $currentIdx,
        ]);
    }
}
