{{-- resources/views/livewire/medical-history-matrix.blade.php --}}

@php
    $cellBg = fn(string $grade) => match(true) {
        $grade === $studentGradeLevel => '#eff6ff',
        array_search($grade, $gradeLevels) < $currentIdx => '#f9fafb',
        default => '#ffffff',
    };
@endphp

<div
    wire:ignore.self
    x-data="{ toastShow: false, toastGrade: '', openCard: null, openMultiSelect: $wire.entangle('openMultiSelect') }"
    x-on:saved.window="toastShow = true; toastGrade = $event.detail.grade; setTimeout(() => toastShow = false, 2500)"
>
<style>
.mhm { font-family: ui-sans-serif, system-ui, sans-serif; font-size: 13px; color: #1e293b; }

.mhm-eyebrow   { margin: 0; font-size: 10px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #94a3b8; }
.mhm-title     { margin: 4px 0 8px; font-size: 17px; font-weight: 700; color: #0f172a; letter-spacing: -.3px; line-height: 1.2; }
.mhm-chips     { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 12px; }
.mhm-chip      { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 11.5px; line-height: 1.6; }
.mhm-chip b    { font-weight: 600; color: #1e293b; }
.mhm-chip-blue { background: #eff6ff; border-color: #bfdbfe; }
.mhm-chip-blue b { color: #1d4ed8; }

.mhm-toggle {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; font-size: 12px; font-weight: 500;
    border-radius: 8px; border: 1px solid #e2e8f0; background: #fff;
    color: #475569; cursor: pointer; transition: .15s;
    white-space: nowrap; font-family: inherit;
}
.mhm-toggle:hover     { background: #f8fafc; border-color: #cbd5e1; }
.mhm-toggle.is-on     { background: #eff6ff; border-color: #93c5fd; color: #1d4ed8; }
.mhm-badge-pill       { display: inline-flex; align-items: center; padding: 1px 7px; background: #dbeafe; color: #1d4ed8; border-radius: 99px; font-size: 10px; font-weight: 700; margin-left: 2px; }

.mhm-pips     { display: flex; gap: 3px; margin-bottom: 14px; }
.mhm-pip      { flex: 1; height: 5px; border-radius: 99px; background: #e2e8f0; }
.mhm-pip.past { background: #93c5fd; }
.mhm-pip.curr { background: #1d4ed8; }

.mhm-scroll   { overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,.05); }

.mhm-tbl      { border-collapse: collapse; width: max-content; min-width: 100%; }

.mhm-tbl .f-col {
    position: sticky; left: 0; z-index: 2;
    min-width: 200px; padding: 7px 14px;
    background: #fff; border-bottom: 1px solid #f1f5f9;
    border-right: 1px solid #cbd5e1;
    font-size: 12px; font-weight: 500; color: #374151;
    white-space: nowrap;
}
.mhm-tbl thead .f-col {
    background: #f8fafc; font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .07em; color: #64748b;
    border-bottom: 2px solid #e2e8f0; padding: 10px 14px; z-index: 3;
}
.mhm-tbl tr:hover .f-col { background: #f8fafc; }

.mhm-tbl .g-th {
    min-width: 100px; padding: 8px 6px 6px;
    text-align: center; font-size: 11.5px; font-weight: 600;
    color: #94a3b8; background: #f8fafc;
    border-bottom: 2px solid #e2e8f0; border-right: 1px solid #cbd5e1;
    white-space: nowrap; line-height: 1.3;
}
.mhm-tbl .g-th { cursor: pointer; transition: all .15s ease; }
.mhm-tbl .g-th:hover { background: #e0e7ff !important; color: #4338ca !important; transform: scale(1.02); }
.mhm-tbl .g-th:hover::after { content: "Edit"; display: block; font-size: 8px; font-weight: 700; margin-top: 4px; color: #4338ca; }
.mhm-tbl .g-th.curr  { background: #eff6ff; border-bottom-color: #1d4ed8; color: #1d4ed8; }
.mhm-tbl .g-th.past  { color: #94a3b8; }
.mhm-tbl .g-th small { display: block; font-size: 9px; margin-top: 2px; }
.mhm-tbl .g-th.curr small { color: #1d4ed8; font-weight: 700; }
.mhm-tbl .g-th.past small { color: #93c5fd; }

.mhm-tbl .d-cell {
    border-bottom: 1px solid #f1f5f9; border-right: 1px solid #cbd5e1;
    padding: 4px 4px; text-align: center; vertical-align: middle;
}
.mhm-tbl tr:hover .d-cell { background: rgba(0,0,0,0.02); }
.mhm-tbl .d-cell.locked  { background: #f8fafc !important; min-width: 72px; }
.mhm-locked-cell { display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 11px; height: 100%; }

.mhm-card {
    border: 1px solid #e2e8f0; border-radius: 10px; margin-bottom: 12px; overflow: hidden;
    background: white;
}
.mhm-card-header {
    display: flex; align-items: center; justify-content: space-between; padding: 12px 16px;
    background: #f8fafc; cursor: pointer; transition: .15s;
}
.mhm-card-header:hover { background: #f1f5f9; }
.mhm-card-header h3 { margin: 0; font-size: 14px; font-weight: 600; color: #1e293b; }
.mhm-card-toggle {
    display: flex; align-items: center; gap: 8px;
}
.mhm-toggle-switch {
    position: relative; width: 40px; height: 22px;
    background: #e2e8f0; border-radius: 11px; cursor: pointer; transition: .2s;
}
.mhm-toggle-switch.is-on { background: #22c55e; }
.mhm-toggle-switch::after {
    content: ''; position: absolute; top: 2px; left: 2px;
    width: 18px; height: 18px; background: white;
    border-radius: 50%; transition: .2s;
}
.mhm-toggle-switch.is-on::after { left: 20px; }

.mhm-card-body {
    padding: 16px; border-top: 1px solid #e2e8f0;
    background: white;
}
.mhm-card-body.collapsed { display: none; }

.mhm-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px; margin-bottom: 12px; }
.mhm-checkbox-item {
    display: flex; align-items: center; gap: 6px;
    padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer;
    font-size: 12px; transition: .15s;
}
.mhm-checkbox-item:hover { border-color: #cbd5e1; }
.mhm-checkbox-item.checked { background: #eff6ff; border-color: #93c5fd; }
.mhm-checkbox-item input { width: 16px; height: 16px; accent-color: #1d4ed8; }

.mhm-textarea {
    width: 100%; min-height: 80px; padding: 10px 12px;
    border: 1px solid #e2e8f0; border-radius: 6px;
    font-size: 13px; font-family: inherit; resize: vertical;
}
.mhm-textarea:focus { outline: none; border-color: #93c5fd; background: #f8fafc; }

.mhm-input {
    width: 100%; padding: 8px 12px;
    border: 1px solid #e2e8f0; border-radius: 6px;
    font-size: 13px; font-family: inherit;
}
.mhm-input:focus { outline: none; border-color: #93c5fd; background: #f8fafc; }

.mhm-radio-group { display: flex; gap: 8px; }
.mhm-radio-btn {
    flex: 1; padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 6px;
    text-align: center; font-size: 12px; font-weight: 500; cursor: pointer; transition: .15s;
}
.mhm-radio-btn:hover { border-color: #cbd5e1; background: #f8fafc; }
.mhm-radio-btn.active { background: #eff6ff; border-color: #1d4ed8; color: #1d4ed8; }

.mhm-save-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 4px;
    width: 100%; padding: 8px 16px; font-size: 12px; font-weight: 600;
    border-radius: 6px; border: 1px solid #bfdbfe; background: #eff6ff;
    color: #1d4ed8; cursor: pointer; white-space: nowrap; font-family: inherit;
    transition: .15s;
}
.mhm-save-btn:hover { background: #dbeafe; }
.mhm-validate-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 4px;
    width: 100%; padding: 8px 16px; font-size: 12px; font-weight: 600;
    border-radius: 6px; border: 1px solid #86efac; background: #f0fdf4;
    color: #16a34a; cursor: pointer; white-space: nowrap; font-family: inherit;
    transition: .15s;
}
.mhm-validate-btn:hover { background: #dcfce7; }

.mhm-toast {
    position: fixed; bottom: 20px; right: 20px; z-index: 9999;
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 16px; background: #f0fdf4; border: 1px solid #86efac;
    border-radius: 8px; color: #15803d; font-size: 13px; font-weight: 500;
    box-shadow: 0 4px 16px rgba(0,0,0,.08);
}

.mhm-hidden-btn {
    display: flex; flex-direction: column; align-items: center; gap: 3px;
    font-size: 10px; font-weight: 600; color: #93c5fd; background: none;
    border: none; cursor: pointer; margin: 0 auto;
}
.mhm-hidden-btn:hover { color: #1d4ed8; }
.th-hidden { min-width: 72px; background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: center; padding: 6px 4px; }

.label-field { font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 6px; text-transform: uppercase; letter-spacing: .05em; }
</style>

<div class="mhm">
{{-- HEADER --}}
<div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:14px;">
    <div>
        <p class="mhm-eyebrow">2019 SHD Form 1-A</p>
        <h2 class="mhm-title">Medical History</h2>
        <div class="mhm-chips">
            <span class="mhm-chip">
                <span>Student</span>
                <b>{{ $studentName }}</b>
            </span>
            @if ($studentGradeLevel)
                <span class="mhm-chip mhm-chip-blue">
                    <span>Current Grade</span>
                    <b>{{ $studentGradeLevel }}</b>
                </span>
            @endif
        </div>
    </div>
    <div style="padding-top:4px;">
        <button wire:click="toggleShowAll" class="mhm-toggle {{ $showAll ? 'is-on' : '' }}">
            @if ($showAll)
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59"/></svg>
                Show Current Only
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Show All Grades
                @if ($hiddenCount > 0)
                    <span class="mhm-badge-pill">+{{ $hiddenCount }}</span>
                @endif
            @endif
        </button>
    </div>
</div>

{{-- GRADE PIPS --}}
<div class="mhm-pips">
    @foreach ($gradeLevels as $grade)
        @php $idx = $loop->index; @endphp
        <div class="mhm-pip {{ $idx < $currentIdx ? 'past' : ($idx === $currentIdx ? 'curr' : '') }}" title="{{ $grade }}"></div>
    @endforeach
</div>

{{-- TOGGLE CARDS --}}
<div style="margin-bottom: 20px;">

@php $sections = [
    'allergies' => ['title' => 'Allergies', 'color' => '#fef2f2', 'border' => '#fecaca'],
    'conditions' => ['title' => 'Medical Conditions', 'color' => '#fefce8', 'border' => '#fef08a'],
    'surgery' => ['title' => 'Past Surgery / Hospitalization', 'color' => '#faf5ff', 'border' => '#e9d5ff'],
    'family' => ['title' => 'Family Health History', 'color' => '#eff6ff', 'border' => '#bfdbfe'],
    'lifestyle' => ['title' => 'Lifestyle', 'color' => '#ecfeff', 'border' => '#a5f3fc'],
]; @endphp

@foreach($gradeLevels as $grade)
@if($this->isVisible($grade))
@php $sectionData = $data[$grade] ?? []; @endphp

<div style="margin-bottom: 16px;">
    <div style="font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px; text-transform: uppercase; letter-spacing: .05em;">
        {{ $grade }} @if($grade === $studentGradeLevel)<span style="color: #1d4ed8;">(Current)</span>@endif
        @if(($sectionData['validated'] ?? false))<span style="display:inline-flex;align-items:center;padding:2px 8px;background:#fef3c7;color:#92400e;border-radius:4px;font-size:10px;font-weight:700;margin-left:8px;">✓ VALIDATED</span>@endif
    </div>

    {{-- ALLERGIES SECTION --}}
    <div class="mhm-card" style="border-color: {{ $sections['allergies']['border'] }};">
        <div class="mhm-card-header" style="background: {{ $sections['allergies']['color'] }};"
             x-on:click="openCard = openCard === 'allergies-{{ $grade }}' ? null : 'allergies-{{ $grade }}'; $wire.toggleAllergyBool('{{ $grade }}')">
            <h3>{{ $sections['allergies']['title'] }}</h3>
            <div class="mhm-card-toggle">
                <span style="font-size: 11px; color: #64748b;">{{ ($sectionData['has_allergies'] ?? false) ? 'ON' : 'OFF' }}</span>
                <div class="mhm-toggle-switch {{ ($sectionData['has_allergies'] ?? false) ? 'is-on' : '' }}"></div>
            </div>
        </div>
        <div class="mhm-card-body" x-show="openCard === 'allergies-{{ $grade }}'" x-transition style="border-color: {{ $sections['allergies']['border'] }};">
            @if($this->canSave($grade))
            <div style="margin-bottom: 12px;">
                <label class="label-field">Types</label>
                <div class="mhm-grid">
                    @foreach(['medicine', 'food', 'dust', 'pollen', 'insect', 'other'] as $allergy)
                    <label class="mhm-checkbox-item {{ in_array($allergy, ($sectionData['allergy_types'] ?? [])) ? 'checked' : '' }}">
                        <input type="checkbox" wire:click="toggleAllergyType('{{ $grade }}', '{{ $allergy }}')" {{ in_array($allergy, ($sectionData['allergy_types'] ?? [])) ? 'checked' : '' }}>
                        <span>{{ ucfirst($allergy) }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="label-field">Other Allergies (specify)</label>
                <input type="text" wire:model.defer="data.{{ $grade }}.allergy_others" placeholder="Enter other allergies..." class="mhm-input">
            </div>
            @else
            <div style="color: #94a3b8; font-size: 12px;">Locked - validated entry</div>
            @endif
        </div>
    </div>

    {{-- MEDICAL CONDITIONS SECTION --}}
    <div class="mhm-card" style="border-color: {{ $sections['conditions']['border'] }};">
        <div class="mhm-card-header" style="background: {{ $sections['conditions']['color'] }};"
             x-on:click="openCard = openCard === 'conditions-{{ $grade }}' ? null : 'conditions-{{ $grade }}'; $wire.toggleConditionBool('{{ $grade }}')">
            <h3>{{ $sections['conditions']['title'] }}</h3>
            <div class="mhm-card-toggle">
                <span style="font-size: 11px; color: #64748b;">{{ ($sectionData['has_medical_conditions'] ?? false) ? 'ON' : 'OFF' }}</span>
                <div class="mhm-toggle-switch {{ ($sectionData['has_medical_conditions'] ?? false) ? 'is-on' : '' }}"></div>
            </div>
        </div>
        <div class="mhm-card-body" x-show="openCard === 'conditions-{{ $grade }}'" x-transition style="border-color: {{ $sections['conditions']['border'] }};">
            @if($this->canSave($grade))
            <div style="margin-bottom: 12px;">
                <label class="label-field">Types</label>
                <div class="mhm-grid">
                    @foreach(['asthma', 'seizure', 'diabetes', 'heart_disease', 'hypertension', 'other'] as $cond)
                    <label class="mhm-checkbox-item {{ in_array($cond, ($sectionData['condition_types'] ?? [])) ? 'checked' : '' }}">
                        <input type="checkbox" wire:click="toggleConditionType('{{ $grade }}', '{{ $cond }}')" {{ in_array($cond, ($sectionData['condition_types'] ?? [])) ? 'checked' : '' }}>
                        <span>{{ ucfirst(str_replace('_', ' ', $cond)) }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="label-field">Other Conditions (specify)</label>
                <input type="text" wire:model.defer="data.{{ $grade }}.condition_others" placeholder="Enter other conditions..." class="mhm-input">
            </div>
            @else
            <div style="color: #94a3b8; font-size: 12px;">Locked - validated entry</div>
            @endif
        </div>
    </div>

    {{-- PAST SURGERY SECTION --}}
    <div class="mhm-card" style="border-color: {{ $sections['surgery']['border'] }};">
        <div class="mhm-card-header" style="background: {{ $sections['surgery']['color'] }};"
             x-on:click="openCard = openCard === 'surgery-{{ $grade }}' ? null : 'surgery-{{ $grade }}'; $wire.toggleSurgeryBool('{{ $grade }}')">
            <h3>{{ $sections['surgery']['title'] }}</h3>
            <div class="mhm-card-toggle">
                <span style="font-size: 11px; color: #64748b;">{{ ($sectionData['has_past_surgery'] ?? false) ? 'ON' : 'OFF' }}</span>
                <div class="mhm-toggle-switch {{ ($sectionData['has_past_surgery'] ?? false) ? 'is-on' : '' }}"></div>
            </div>
        </div>
        <div class="mhm-card-body" x-show="openCard === 'surgery-{{ $grade }}'" x-transition style="border-color: {{ $sections['surgery']['border'] }};">
            @if($this->canSave($grade))
            <div>
                <label class="label-field">Surgery / Hospitalization Details</label>
                <textarea wire:model.defer="data.{{ $grade }}.surgery_details" placeholder="Describe the surgery or hospitalization..." class="mhm-textarea"></textarea>
            </div>
            @else
            <div style="color: #94a3b8; font-size: 12px;">Locked - validated entry</div>
            @endif
        </div>
    </div>

    {{-- FAMILY HISTORY SECTION (always visible grid) --}}
    <div class="mhm-card" style="border-color: {{ $sections['family']['border'] }};">
        <div class="mhm-card-header" style="background: {{ $sections['family']['color'] }};">
            <h3>{{ $sections['family']['title'] }}</h3>
        </div>
        <div class="mhm-card-body" style="border-color: {{ $sections['family']['border'] }};">
            @if($this->canSave($grade))
            <div style="margin-bottom: 12px;">
                <label class="label-field">Conditions</label>
                <div class="mhm-grid">
                    @foreach(['hypertension', 'diabetes', 'cancer', 'asthma', 'heart_disease', 'other'] as $fh)
                    <label class="mhm-checkbox-item {{ in_array($fh, ($sectionData['family_history'] ?? [])) ? 'checked' : '' }}">
                        <input type="checkbox" wire:click="toggleFamilyHistory('{{ $grade }}', '{{ $fh }}')" {{ in_array($fh, ($sectionData['family_history'] ?? [])) ? 'checked' : '' }}>
                        <span>{{ ucfirst(str_replace('_', ' ', $fh)) }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @if(in_array('cancer', ($sectionData['family_history'] ?? [])))
            <div style="margin-bottom: 12px;">
                <label class="label-field">Cancer Type</label>
                <input type="text" wire:model.defer="data.{{ $grade }}.cancer_type" placeholder="Specify cancer type..." class="mhm-input">
            </div>
            @endif
            <div>
                <label class="label-field">Other Family History</label>
                <input type="text" wire:model.defer="data.{{ $grade }}.family_history_other" placeholder="Other conditions..." class="mhm-input">
            </div>
            @else
            <div style="color: #94a3b8; font-size: 12px;">Locked - validated entry</div>
            @endif
        </div>
    </div>

    {{-- LIFESTYLE SECTION --}}
    <div class="mhm-card" style="border-color: {{ $sections['lifestyle']['border'] }};">
        <div class="mhm-card-header" style="background: {{ $sections['lifestyle']['color'] }};">
            <h3>{{ $sections['lifestyle']['title'] }}</h3>
        </div>
        <div class="mhm-card-body" style="padding: 16px; display: flex; gap: 24px; flex-wrap: wrap;" x-show="true" x-transition>
            @if($this->canSave($grade))
            <div style="flex: 1; min-width: 150px;">
                <label class="label-field">Smoke Exposure</label>
                <label class="mhm-checkbox-item {{ ($sectionData['smoke_exposure'] ?? false) ? 'checked' : '' }}" style="border-color: {{ $sections['lifestyle']['border'] }};">
                    <input type="checkbox" wire:model.defer="data.{{ $grade }}.smoke_exposure">
                    <span>Second-hand smoke exposure</span>
                </label>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label class="label-field">Dominant Hand</label>
                <div class="mhm-radio-group">
                    <button type="button" wire:click="setDominantHand('{{ $grade }}', 'right')" 
                            class="mhm-radio-btn {{ ($sectionData['dominant_hand'] ?? '') === 'right' ? 'active' : '' }}">Right</button>
                    <button type="button" wire:click="setDominantHand('{{ $grade }}', 'left')" 
                            class="mhm-radio-btn {{ ($sectionData['dominant_hand'] ?? '') === 'left' ? 'active' : '' }}">Left</button>
                    <button type="button" wire:click="setDominantHand('{{ $grade }}', 'both')" 
                            class="mhm-radio-btn {{ ($sectionData['dominant_hand'] ?? '') === 'both' ? 'active' : '' }}">Both</button>
                </div>
            </div>
            @else
            <div style="color: #94a3b8; font-size: 12px;">Locked - validated entry</div>
            @endif
        </div>
    </div>

    {{-- SAVE AND VALIDATE BUTTONS --}}
    <div style="display: flex; gap: 8px; margin-top: 12px;">
        @if($this->canSave($grade))
        <button wire:click="performSave({{ $loop->index }})" wire:loading.attr="disabled" wire:target="performSave({{ $loop->index }})" class="mhm-save-btn" style="flex: 1;">
            <span wire:loading.remove wire:target="performSave({{ $loop->index }})">Save {{ $grade }}</span>
            <span wire:loading wire:target="performSave({{ $loop->index }})">Saving...</span>
        </button>
        @else
        <div style="flex: 1; padding: 8px 16px; background: #fef3c7; border: 1px solid #fcd34d; border-radius: 6px; color: #92400e; font-size: 12px; font-weight: 600; text-align: center;">Locked</div>
        @endif

        @if($this->isAdmin())
            @if(($sectionData['validated'] ?? false))
            <button wire:click="invalidateEntryForGrade('{{ $grade }}')" class="mhm-validate-btn" style="flex: 1; background: #fef3c7; border-color: #fcd34d; color: #92400e;">Invalidate</button>
            @else
            <button wire:click="validateEntryForGrade('{{ $grade }}')" wire:loading.attr="disabled" wire:target="validateEntryForGrade('{{ $grade }}')" class="mhm-validate-btn" style="flex: 1;">
                <span wire:loading.remove wire:target="validateEntryForGrade('{{ $grade }}')">Validate</span>
                <span wire:loading wire:target="validateEntryForGrade('{{ $grade }}')">Validating...</span>
            </button>
            @endif
        @elseif(!($sectionData['validated'] ?? false))
        <button wire:click="validateEntryForGrade('{{ $grade }}')" wire:loading.attr="disabled" wire:target="validateEntryForGrade('{{ $grade }}')" class="mhm-validate-btn" style="flex: 1;">
            <span wire:loading.remove wire:target="validateEntryForGrade('{{ $grade }}')">Validate</span>
            <span wire:loading wire:target="validateEntryForGrade('{{ $grade }}')">Validating...</span>
        </button>
        @endif
    </div>

</div>
@endif
@endforeach

</div>

{{-- TOAST --}}
<div x-show="toastShow" x-transition x-cloak class="mhm-toast">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
    <span x-text="toastGrade + ' saved successfully'"></span>
</div>
</div>