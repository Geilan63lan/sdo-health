<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolClinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'clinic_name',
        'location',
        'head_nurse_name',
        'nurse_contact',
        'bed_count',
        'equipment_inventory',
        'operating_hours',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
