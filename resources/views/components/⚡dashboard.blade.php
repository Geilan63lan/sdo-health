<?php

use Livewire\Component;
use App\Models\Student;
use App\Models\HealthRecord;
use App\Models\HealthProgram;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public function with(): array
    {
        $user = Auth::user();
        $isSdoAdmin = $user->role === 'sdo_admin';
        $schoolId = $user->school_id;

        $studentQuery = Student::query();
        $recordQuery = HealthRecord::query();
        $programQuery = HealthProgram::query();

        if (!$isSdoAdmin && $schoolId) {
            $studentQuery->where('school_id', $schoolId);
            $recordQuery->whereHas('student', fn ($q) => $q->where('school_id', $schoolId));
            $programQuery->where('school_id', $schoolId);
        }

        return [
            'isSdoAdmin' => $isSdoAdmin,
            'schoolName' => $isSdoAdmin ? 'SDO Wide' : ($user->school?->name ?? 'No School Assigned'),
            'stats' => [
                [
                    'label' => 'Total Students',
                    'value' => $studentQuery->count(),
                    'description' => 'Enrolled students',
                    'icon' => 'users',
                    'color' => 'blue',
                ],
                [
                    'label' => 'Health Records',
                    'value' => $recordQuery->count(),
                    'description' => 'Medical checkups',
                    'icon' => 'heart',
                    'color' => 'red',
                ],
                [
                    'label' => 'Active Programs',
                    'value' => $programQuery->where('status', 'active')->count(),
                    'description' => 'Ongoing initiatives',
                    'icon' => 'clipboard-document-check',
                    'color' => 'emerald',
                ],
            ],
            'recentRecords' => $recordQuery->with('student')->latest()->limit(5)->get(),
        ];
    }
};
?>

<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" level="1">Dashboard Overview</flux:heading>
            <flux:subheading>{{ $schoolName }}</flux:subheading>
        </div>

        <flux:button href="/admin" icon="cog-6-tooth" variant="primary">
            Go to Admin Panel
        </flux:button>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        @foreach ($stats as $stat)
            <flux:card class="flex items-center gap-4">
                <div class="flex size-12 items-center justify-center rounded-lg bg-{{ $stat['color'] }}-100 text-{{ $stat['color'] }}-600 dark:bg-{{ $stat['color'] }}-900/30 dark:text-{{ $stat['color'] }}-400">
                    <flux:icon :name="$stat['icon']" />
                </div>

                <div>
                    <flux:text size="sm" class="font-medium">{{ $stat['label'] }}</flux:text>
                    <flux:heading size="xl">{{ $stat['value'] }}</flux:heading>
                    <flux:text size="xs" class="mt-1">{{ $stat['description'] }}</flux:text>
                </div>
            </flux:card>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <flux:card>
            <div class="mb-4 flex items-center justify-between">
                <flux:heading size="lg">Recent Health Records</flux:heading>
                <flux:button href="/admin/health-records" variant="ghost" size="sm">View all</flux:button>
            </div>

            <div class="space-y-4">
                @forelse ($recentRecords as $record)
                    <div class="flex items-center justify-between rounded-lg border border-neutral-200 p-3 dark:border-neutral-800">
                        <div>
                            <flux:text class="font-medium">{{ $record->student->full_name }}</flux:text>
                            <flux:text size="xs">{{ $record->record_date->format('M d, Y') }} - BMI: {{ $record->bmi }}</flux:text>
                        </div>
                        <flux:badge :color="$record->bmi_category === 'Normal' ? 'emerald' : 'amber'">
                            {{ $record->bmi_category }}
                        </flux:badge>
                    </div>
                @empty
                    <flux:text class="py-4 text-center italic">No records found</flux:text>
                @endforelse
            </div>
        </flux:card>

        <flux:card>
            <flux:heading size="lg" class="mb-4">Quick Actions</flux:heading>
            
            <div class="grid grid-cols-2 gap-4">
                <flux:button href="/admin/students/create" icon="user-plus" class="justify-start">Add Student</flux:button>
                <flux:button href="/admin/health-records/create" icon="plus" class="justify-start">New Record</flux:button>
                <flux:button href="/admin/vaccinations/create" icon="beaker" class="justify-start">Vaccination</flux:button>
                <flux:button href="/admin/absences/create" icon="calendar-days" class="justify-start">Log Absence</flux:button>
            </div>
        </flux:card>
    </div>
</div>
