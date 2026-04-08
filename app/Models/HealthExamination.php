<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'examined_by',
        'examiner_name',
        'grade_level',
        'date_of_examination',
        'designation',
        'height_cm',
        'weight_kg',
        'ns_bmi_for_age',
        'ns_height_for_age',
        'is_4ps_beneficiary',
        'is_sbfp_beneficiary',
        'deworming_july',
        'deworming_january',
        'iron_supplementation',
        'immunization_kind',
        'menarche',
        'temperature',
        'blood_pressure',
        'pulse_rate',
        'respiratory_rate',
        'vision_l',
        'vision_r',
        'auditory_l',
        'auditory_r',
        'skin_scalp',
        'eyes_ears_nose',
        'mouth_neck_throat',
        'lungs_heart',
        'abdomen',
        'deformities',
        'others_specify',
        'medications',
        'validated',
        'validated_at',
        'validated_by',
        'reverted_at',
        'reverted_by',
    ];

    protected function casts(): array
    {
        return [
            'date_of_examination' => 'date',
            'height_cm' => 'decimal:2',
            'weight_kg' => 'decimal:2',
            'is_4ps_beneficiary' => 'boolean',
            'is_sbfp_beneficiary' => 'boolean',
            'deworming_july' => 'boolean',
            'deworming_january' => 'boolean',
            'iron_supplementation' => 'boolean',
            'medications' => 'string',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function examinedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'examined_by');
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function revertedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reverted_by');
    }

    public function isValidated(): bool
    {
        return $this->validated === true;
    }

    public function isReverted(): bool
    {
        return $this->reverted_at !== null;
    }

    public function canEdit(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        if ($this->isValidated() && ! $this->isReverted()) {
            return $user->isAdmin();
        }

        return ! $this->isValidated() || $user->isAdmin();
    }

    public function scopeValidated($query)
    {
        return $query->where('validated', true);
    }

    public function scopeUnvalidated($query)
    {
        return $query->where('validated', false)->orWhereNull('validated');
    }

    public function validate(?User $user): void
    {
        $this->validated = true;
        $this->validated_at = now();
        $this->validated_by = $user?->id;
        $this->save();
    }

    public function revert(?User $user): void
    {
        $this->validated = false;
        $this->validated_at = null;
        $this->validated_by = null;
        $this->reverted_at = now();
        $this->reverted_by = $user?->id;
        $this->save();
    }

    public function invalidate(?User $user): void
    {
        $this->validated = false;
        $this->validated_at = null;
        $this->validated_by = null;
        $this->reverted_at = now();
        $this->reverted_by = $user?->id;
        $this->save();
    }
}
