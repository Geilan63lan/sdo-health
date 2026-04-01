<?php

namespace App\Policies;

use App\Models\HealthExamination;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HealthExaminationPolicy
{
    public function viewAny(User $user): Response
    {
        if ($user->hasRole('sdo_admin')
            || $user->hasPermissionTo('view_health_records')
            || $user->hasPermissionTo('manage_health_records')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view health examinations.');
    }

    public function view(User $user, HealthExamination $healthExamination): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        $hasSchoolAccess = $healthExamination->student->school_id === $user->school_id;

        if ($hasSchoolAccess && ($user->hasPermissionTo('view_health_records') || $user->hasPermissionTo('manage_health_records'))) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view this health examination.');
    }

    public function create(User $user): Response
    {
        if ($user->hasRole('sdo_admin') || $user->hasPermissionTo('manage_health_records')) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to create health examinations.');
    }

    public function update(User $user, HealthExamination $healthExamination): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        if ($user->hasPermissionTo('manage_health_records') && $healthExamination->student->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to update this health examination.');
    }

    public function delete(User $user, HealthExamination $healthExamination): Response
    {
        if ($user->hasRole('sdo_admin')) {
            return Response::allow();
        }

        if ($user->hasPermissionTo('manage_health_records') && $healthExamination->student->school_id === $user->school_id) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to delete this health examination.');
    }

    public function restore(User $user, HealthExamination $healthExamination): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to restore health examinations.');
    }

    public function forceDelete(User $user, HealthExamination $healthExamination): Response
    {
        return $user->hasRole('sdo_admin')
            ? Response::allow()
            : Response::deny('You do not have permission to permanently delete health examinations.');
    }
}
