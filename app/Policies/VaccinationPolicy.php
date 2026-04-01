<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vaccination;
use Illuminate\Auth\Access\Response;

class VaccinationPolicy
{
    public function viewAny(User $user): Response
    {
        if ($user->hasRole('sdo_admin')
            || $user->hasPermissionTo('view_vaccinations')
            || $user->hasPermissionTo('manage_vaccinations')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view vaccinations.');
    }

    public function view(User $user, Vaccination $vaccination): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        $hasSchoolAccess = $vaccination->student->school_id === $user->school_id;

        if ($hasSchoolAccess && ($user->hasPermissionTo('view_vaccinations') || $user->hasPermissionTo('manage_vaccinations'))) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view this vaccination.');
    }

    public function create(User $user): Response
    {
        if ($user->hasRole('sdo_admin') || $user->hasPermissionTo('manage_vaccinations')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to create vaccinations.');
    }

    public function update(User $user, Vaccination $vaccination): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        if ($user->hasPermissionTo('manage_vaccinations') && $vaccination->student->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to update this vaccination.');
    }

    public function delete(User $user, Vaccination $vaccination): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        if ($user->hasPermissionTo('manage_vaccinations') && $vaccination->student->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to delete this vaccination.');
    }

    public function restore(User $user, Vaccination $vaccination): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to restore vaccinations.');
    }

    public function forceDelete(User $user, Vaccination $vaccination): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to permanently delete vaccinations.');
    }
}
