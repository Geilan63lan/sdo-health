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
}
