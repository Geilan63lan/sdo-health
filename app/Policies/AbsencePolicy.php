<?php

namespace App\Policies;

use App\Models\Absence;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AbsencePolicy
{
    public function viewAny(User $user): Response
    {
        if ($user->hasRole('sdo_admin') || $user->hasPermissionTo('view_absences')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view absences.');
    }

    public function view(User $user, Absence $absence): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        if ($user->hasPermissionTo('view_absences') && $absence->student->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view this absence.');
    }

    public function create(User $user): Response
    {
        return Response::deny('You do not have permission to create absences.');
    }

    public function update(User $user, Absence $absence): Response
    {
        return Response::deny('You do not have permission to update absences.');
    }

    public function delete(User $user, Absence $absence): Response
    {
        return Response::deny('You do not have permission to delete absences.');
    }

    public function restore(User $user, Absence $absence): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to restore absences.');
    }

    public function forceDelete(User $user, Absence $absence): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to permanently delete absences.');
    }
}
