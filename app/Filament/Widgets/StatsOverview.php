<?php

namespace App\Filament\Widgets;

use App\Models\HealthProgram;
use App\Models\HealthRecord;
use App\Models\School;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        $isSdoAdmin = $user->role === 'sdo_admin';
        $schoolId = $user->school_id;

        $stats = [];

        if ($isSdoAdmin) {
            $stats[] = Stat::make('Total Schools', School::count())
                ->description('Active educational institutions')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('primary');
        }

        $studentQuery = Student::query();
        $recordQuery = HealthRecord::query();
        $programQuery = HealthProgram::query();

        if (!$isSdoAdmin && $schoolId) {
            $studentQuery->where('school_id', $schoolId);
            $recordQuery->whereHas('student', fn ($q) => $q->where('school_id', $schoolId));
            $programQuery->where('school_id', $schoolId);
        }

        $stats[] = Stat::make('Total Students', $studentQuery->count())
            ->description('Enrolled students')
            ->descriptionIcon('heroicon-m-users')
            ->chart([7, 3, 4, 5, 6, 3, 5, 3])
            ->color('success');

        $stats[] = Stat::make('Health Records', $recordQuery->count())
            ->description('Total medical checkups')
            ->descriptionIcon('heroicon-m-heart')
            ->color('danger');

        $stats[] = Stat::make('Active Programs', $programQuery->where('status', 'active')->count())
            ->description('Ongoing health initiatives')
            ->descriptionIcon('heroicon-m-clipboard-document-check')
            ->color('warning');

        return $stats;
    }
}
