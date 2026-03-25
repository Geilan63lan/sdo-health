<?php

namespace App\Policies;

use App\Models\HealthExamination;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HealthExaminationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to view health examinations.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, HealthExamination $healthExamination): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        if ($healthExamination->student->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view this health examination.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, HealthExamination $healthExamination): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        if ($healthExamination->student->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to update this health examination.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, HealthExamination $healthExamination): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        if ($healthExamination->student->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to delete this health examination.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, HealthExamination $healthExamination): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to restore health examinations.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, HealthExamination $healthExamination): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to permanently delete health examinations.');
    }
}
