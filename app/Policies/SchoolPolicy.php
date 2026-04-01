<?php

namespace App\Policies;

use App\Models\School;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchoolPolicy
{
    public function viewAny(User $user): Response
    {
        if ($user->hasRole('sdo_admin') || $user->hasPermissionTo('view_schools')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view schools.');
    }

    public function view(User $user, School $school): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        if ($user->hasPermissionTo('view_schools') && $user->school_id === $school->id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view this school.');
    }

    public function create(User $user): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to create schools.');
    }

    public function update(User $user, School $school): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to update schools.');
    }

    public function delete(User $user, School $school): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to delete schools.');
    }

    public function restore(User $user, School $school): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to restore schools.');
    }

    public function forceDelete(User $user, School $school): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to permanently delete schools.');
    }
}
