<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

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

    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Only allow users with specific roles to access the admin panel
        if ($panel->getId() === 'admin') {
            // Check if user is verified
            if ($this->email_verified_at === null) {
                return false;
            }

            // Roles allowed to access the panel (Approved or not, handled by middleware)
            return $this->hasRole('sdo_admin')
                || $this->hasRole('health_coordinator')
                || $this->hasRole('principal');
        }

        return true;
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
