<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalHistoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'grade_level',
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
        'validated',
        'validated_at',
        'validated_by',
        'invalidated_at',
        'invalidated_by',
    ];

    protected function casts(): array
    {
        return [
            'has_allergies' => 'boolean',
            'allergy_types' => 'array',
            'has_medical_conditions' => 'boolean',
            'condition_types' => 'array',
            'has_past_surgery' => 'boolean',
            'family_history' => 'array',
            'smoke_exposure' => 'boolean',
            'validated' => 'boolean',
            'validated_at' => 'datetime',
            'invalidated_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function invalidatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invalidated_by');
    }

    public function isValidated(): bool
    {
        return $this->validated === true;
    }

    public function isInvalidated(): bool
    {
        return $this->invalidated_at !== null;
    }

    public function canEdit(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        if ($this->isValidated() && ! $this->isInvalidated()) {
            return $user->isAdmin();
        }

        return ! $this->isValidated() || $user->isAdmin();
    }

    public function validate(?User $user): void
    {
        $this->validated = true;
        $this->validated_at = now();
        $this->validated_by = $user?->id;
        $this->save();
    }

    public function invalidate(?User $user): void
    {
        $this->validated = false;
        $this->validated_at = null;
        $this->validated_by = null;
        $this->invalidated_at = now();
        $this->invalidated_by = $user?->id;
        $this->save();
    }
}
