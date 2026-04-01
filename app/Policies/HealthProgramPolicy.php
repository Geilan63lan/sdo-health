<?php

namespace App\Policies;

use App\Models\HealthProgram;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HealthProgramPolicy
{
    public function viewAny(User $user): Response
    {
        if ($user->hasRole('sdo_admin')
            || $user->hasPermissionTo('view_health_programs')
            || $user->hasPermissionTo('manage_health_programs')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view health programs.');
    }

    public function view(User $user, HealthProgram $healthProgram): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        $hasSchoolAccess = $healthProgram->school_id === $user->school_id;

        if ($hasSchoolAccess && ($user->hasPermissionTo('view_health_programs') || $user->hasPermissionTo('manage_health_programs'))) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view this health program.');
    }

    public function create(User $user): Response
    {
        if ($user->hasRole('sdo_admin') || $user->hasPermissionTo('manage_health_programs')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to create health programs.');
    }

    public function update(User $user, HealthProgram $healthProgram): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        if ($user->hasPermissionTo('manage_health_programs') && $healthProgram->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to update this health program.');
    }

    public function delete(User $user, HealthProgram $healthProgram): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        if ($user->hasPermissionTo('manage_health_programs') && $healthProgram->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to delete this health program.');
    }

    public function restore(User $user, HealthProgram $healthProgram): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to restore health programs.');
    }

    public function forceDelete(User $user, HealthProgram $healthProgram): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to permanently delete health programs.');
    }
}
