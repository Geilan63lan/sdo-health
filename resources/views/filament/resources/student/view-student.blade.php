{{-- resources/views/filament/resources/student/view-student.blade.php --}}
<x-filament-panels::page>

    {{ $this->infolist }}

    <div class="mt-6">
        {{-- Pass the student ID (int) — not the model.
             Livewire 3 safely persists primitives between requests.
             Passing a model directly causes re-hydration failures. --}}
        @livewire('health-examination-matrix', ['studentId' => $record->id], key('hem-' . $record->id))
    </div>

</x-filament-panels::page>