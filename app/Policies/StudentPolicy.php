<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        // Health Coordinators and Principals can view students from their school
        if ($user->hasRole(['health_coordinator', 'principal'])) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view students.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Student $student): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        // Check if the student belongs to the user's school
        if ($student->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view this student.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        // Health Coordinators and Principals can create students for their school
        if ($user->hasRole(['health_coordinator', 'principal'])) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to create students.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Student $student): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        // Check if the student belongs to the user's school
        if ($student->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to update this student.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Student $student): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        // Check if the student belongs to the user's school
        if ($student->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to delete this student.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Student $student): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to restore students.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Student $student): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to permanently delete students.');
    }
}
