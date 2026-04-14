{{-- resources/views/filament/resources/student/view-student.blade.php --}}
<x-filament-panels::page>

    {{ $this->infolist }}

    <div class="mt-6">
        @livewire('medical-history-matrix', ['studentId' => $record->id], key('mhm-' . $record->id))
    </div>

    <div class="mt-6">
        @livewire('health-examination-matrix', ['studentId' => $record->id], key('hem-' . $record->id))
    </div>

</x-filament-panels::page>