<x-filament-panels::page>
    {{ $this->infolist }}

    <div class="mt-6">
        @livewire('health-examination-matrix', ['record' => $record->id])
    </div>
</x-filament-panels::page>