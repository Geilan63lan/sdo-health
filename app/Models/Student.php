<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'lrn',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birth_date',
        'sex',
        'address',
        'guardian_name',
        'guardian_contact',
        'guardian_relationship',
        'is_active',
        'current_grade_level',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function vaccinations(): HasMany
    {
        return $this->hasMany(Vaccination::class);
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    public function programParticipations(): HasMany
    {
        return $this->hasMany(ProgramParticipation::class);
    }

    public function medicalHistory(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MedicalHistory::class);
    }

    public function healthExaminations(): HasMany
    {
        return $this->hasMany(HealthExamination::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name} {$this->suffix}");
    }
}
