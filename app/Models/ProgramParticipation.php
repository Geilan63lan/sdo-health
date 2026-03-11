<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramParticipation extends Model
{
    use HasFactory;

    protected $fillable = [
        'health_program_id',
        'student_id',
        'participation_date',
        'outcome',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'participation_date' => 'date',
    ];

    public function healthProgram(): BelongsTo
    {
        return $this->belongsTo(HealthProgram::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
