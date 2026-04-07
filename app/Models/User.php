<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use App\Models\School;
use App\Models\HealthRecord;
use App\Models\Vaccination;
use App\Models\Absence;
use App\Models\HealthProgram;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, HasRoles, HasPermissions, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'school_id',
        'is_approved',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_approved' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    // TODO: HealthRecord was replaced by HealthExamination
    // Kept for reference - commented out to prevent errors
    // public function healthRecords(): HasMany
    // {
    //     return $this->hasMany(HealthRecord::class, 'recorded_by');
    // }

    public function vaccinations(): HasMany
    {
        return $this->hasMany(Vaccination::class, 'recorded_by');
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class, 'recorded_by');
    }

    public function healthPrograms(): HasMany
    {
        return $this->hasMany(HealthProgram::class, 'coordinator_id');
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_approved && $this->email_verified_at;
    }

    public function canViewPanel(Panel $panel): bool
    {
        return $this->hasRole(['sdo_admin', 'health_coordinator', 'principal']);
    }

    /**
     * Override Spatie's permissions relation to include expires_at pivot column.
     */
    public function permissions(): MorphToMany
    {
        $relation = $this->morphToMany(
            config('permission.models.permission'),
            'model',
            config('permission.table_names.model_has_permissions'),
            config('permission.column_names.model_morph_key'),
        )->withPivot('expires_at');

        return $relation;
    }

    /**
     * Override Spatie's getDirectPermissions to exclude expired temporary grants.
     */
    public function getDirectPermissions(): Collection
    {
        return $this->permissions->filter(function (Permission $permission) {
            $expiresAt = $permission->pivot->expires_at;

            return $expiresAt === null || now()->lt($expiresAt);
        });
    }

    /**
     * Override Spatie's hasDirectPermission to exclude expired temporary grants.
     */
    public function hasDirectPermission($permission): bool
    {
        $permission = $this->filterPermission($permission);

        return $this->getDirectPermissions()
            ->contains($permission->getKeyName(), $permission->getKey());
    }

    /**
     * Override Spatie's getAllPermissions to exclude expired temporary grants.
     */
    public function getAllPermissions(): Collection
    {
        $permissions = $this->getDirectPermissions();

        if (! $this instanceof Permission) {
            $permissions = $permissions->merge($this->getPermissionsViaRoles());
        }

        return $permissions->sort()->values();
    }

    /**
     * Get all direct permissions including expired ones (for admin display purposes).
     */
    public function getAllDirectPermissions(): Collection
    {
        return $this->permissions;
    }
}
