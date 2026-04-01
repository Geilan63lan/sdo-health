<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
    public function viewAny(User $user): Response
    {
        if ($user->hasRole('sdo_admin')
            || $user->hasPermissionTo('view_students')
            || $user->hasPermissionTo('manage_students')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view students.');
    }

    public function view(User $user, Student $student): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        $hasSchoolAccess = $student->school_id === $user->school_id;

        if ($hasSchoolAccess && ($user->hasPermissionTo('view_students') || $user->hasPermissionTo('manage_students'))) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view this student.');
    }

    public function create(User $user): Response
    {
        if ($user->hasRole('sdo_admin') || $user->hasPermissionTo('manage_students')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to create students.');
    }

    public function update(User $user, Student $student): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        if ($user->hasPermissionTo('manage_students') && $student->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to update this student.');
    }

    public function delete(User $user, Student $student): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        if ($user->hasPermissionTo('manage_students') && $student->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to delete this student.');
    }

    public function restore(User $user, Student $student): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to restore students.');
    }

    public function forceDelete(User $user, Student $student): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to permanently delete students.');
    }
}
