<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'address',
        'level',
        'contact_number',
        'email',
        'principal_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function schoolClinics(): HasMany
    {
        return $this->hasMany(SchoolClinic::class);
    }

    public function healthPrograms(): HasMany
    {
        return $this->hasMany(HealthProgram::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function healthCoordinators(): HasMany
    {
        return $this->hasMany(SchoolHealthCoordinator::class);
    }
}
