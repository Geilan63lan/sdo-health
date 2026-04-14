{{-- resources/views/livewire/health-examination-matrix.blade.php --}}

@php
    $cellBg = fn(string $grade) => match(true) {
        $grade === $studentGradeLevel => '#eff6ff',
        array_search($grade, $gradeLevels) < $currentIdx => '#f9fafb',
        default => '#ffffff',
    };
@endphp

{{-- ═══════════════════════════════════════════════════════
     SCOPED STYLES — loaded inline, always works in Filament
═══════════════════════════════════════════════════════ --}}
<div
    wire:ignore.self
    x-data="{ toastShow: false, toastGrade: '', openMultiSelect: $wire.entangle('openMultiSelect') }"
    x-on:hem-saved.window="toastShow = true; toastGrade = $event.detail.grade; setTimeout(() => toastShow = false, 2500)"
>
<style>
.hem { font-family: ui-sans-serif, system-ui, sans-serif; font-size: 13px; color: #1e293b; position: relative; }

/* Header */
.hem-eyebrow   { margin: 0; font-size: 10px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #94a3b8; }
.hem-title     { margin: 4px 0 8px; font-size: 17px; font-weight: 700; color: #0f172a; letter-spacing: -.3px; line-height: 1.2; }
.hem-chips     { display: flex; gap: 6px; flex-wrap: wrap; }
.hem-chip      { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 11.5px; line-height: 1.6; }
.hem-chip b    { font-weight: 600; color: #1e293b; }
.hem-chip span { color: #94a3b8; }
.hem-chip-blue { background: #eff6ff; border-color: #bfdbfe; }
.hem-chip-blue b { color: #1d4ed8; }
.hem-chip-blue span { color: #93c5fd; }

/* Toggle button */
.hem-toggle {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; font-size: 12px; font-weight: 500;
    border-radius: 8px; border: 1px solid #e2e8f0; background: #fff;
    color: #475569; cursor: pointer; transition: .15s;
    white-space: nowrap; font-family: inherit;
}
.hem-toggle:hover     { background: #f8fafc; border-color: #cbd5e1; }
.hem-toggle.is-on     { background: #eff6ff; border-color: #93c5fd; color: #1d4ed8; }
.hem-toggle svg       { width: 14px; height: 14px; flex-shrink: 0; }
.hem-badge-pill       { display: inline-flex; align-items: center; padding: 1px 7px; background: #dbeafe; color: #1d4ed8; border-radius: 99px; font-size: 10px; font-weight: 700; margin-left: 2px; }

/* Grade pips */
.hem-pips     { display: flex; gap: 3px; margin-bottom: 14px; }
.hem-pip      { flex: 1; height: 5px; border-radius: 99px; background: #e2e8f0; }
.hem-pip.past { background: #93c5fd; }
.hem-pip.curr { background: #1d4ed8; }

/* Scroll wrapper */
.hem-scroll   { overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,.05); }

/* Table */
.hem-tbl      { border-collapse: collapse; width: max-content; min-width: 100%; }

/* Sticky field column */
.hem-tbl .f-col {
    position: sticky; left: 0; z-index: 2;
    min-width: 200px; padding: 7px 14px;
    background: #fff; border-bottom: 1px solid #f1f5f9;
    border-right: 1px solid #cbd5e1;
    font-size: 12px; font-weight: 500; color: #374151;
    white-space: nowrap;
}
.hem-tbl thead .f-col {
    background: #f8fafc; font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .07em; color: #64748b;
    border-bottom: 2px solid #e2e8f0; padding: 10px 14px; z-index: 3;
}
.hem-tbl tr:hover .f-col { background: #f8fafc; }

/* Grade header cells - clickable */
.hem-tbl .g-th {
    min-width: 100px; padding: 8px 6px 6px;
    text-align: center; font-size: 11.5px; font-weight: 600;
    color: #94a3b8; background: #f8fafc;
    border-bottom: 2px solid #e2e8f0; border-right: 1px solid #cbd5e1;
    white-space: nowrap; line-height: 1.3;
}
.hem-tbl .g-th { 
    cursor: pointer; 
    transition: all .15s ease; 
}
.hem-tbl .g-th:hover { 
    background: #e0e7ff !important; 
    color: #4338ca !important;
    transform: scale(1.02);
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.2);
}
.hem-tbl .g-th:hover::after {
    content: "Edit";
    display: block;
    font-size: 8px;
    font-weight: 700;
    margin-top: 4px;
    color: #4338ca;
}
.hem-tbl .g-th.curr  { background: #eff6ff; border-bottom-color: #1d4ed8; color: #1d4ed8; }
.hem-tbl .g-th.past  { color: #94a3b8; }
.hem-tbl .g-th small { display: block; font-size: 9px; margin-top: 2px; }
.hem-tbl .g-th.curr small { color: #1d4ed8; font-weight: 700; }
.hem-tbl .g-th.past small { color: #93c5fd; }

/* Validation badges */
.hem-validated-badge { display: inline-flex; align-items: center; gap: 3px; padding: 2px 6px; background: #fef3c7; color: #92400e; border-radius: 4px; font-size: 9px; font-weight: 700; }
.hem-reverted-badge { display: inline-flex; align-items: center; gap: 3px; padding: 2px 6px; background: #dbeafe; color: #1e40af; border-radius: 4px; font-size: 9px; font-weight: 700; }

/* Hidden column */
.hem-tbl .th-hidden {
    min-width: 72px; background: #f8fafc; border-bottom: 2px solid #e2e8f0;
    text-align: center; padding: 6px 4px;
}
.hem-hidden-btn {
    display: flex; flex-direction: column; align-items: center; gap: 3px;
    font-size: 10px; font-weight: 600; color: #93c5fd; background: none;
    border: none; cursor: pointer; font-family: inherit; margin: 0 auto;
    transition: color .15s;
}
.hem-hidden-btn:hover { color: #1d4ed8; }
.hem-hidden-btn svg   { width: 14px; height: 14px; }

/* Section headers */
.hem-sec td { padding: 6px 14px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .09em; }
.s-blue   td { background: #eff6ff; color: #1d4ed8; border-top: 1px solid #bfdbfe; border-bottom: 1px solid #bfdbfe; }
.s-green  td { background: #f0fdf4; color: #15803d; border-top: 1px solid #bbf7d0; border-bottom: 1px solid #bbf7d0; }
.s-violet td { background: #f5f3ff; color: #6d28d9; border-top: 1px solid #ddd6fe; border-bottom: 1px solid #ddd6fe; }
.s-teal   td { background: #f0fdfa; color: #0f766e; border-top: 1px solid #99f6e4; border-bottom: 1px solid #99f6e4; }
.s-orange td { background: #fff7ed; color: #c2410c; border-top: 1px solid #fed7aa; border-bottom: 1px solid #fed7aa; }
.s-gray   td { background: #f8fafc; color: #64748b; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; }

/* Data cells */
.hem-tbl .d-cell {
    border-bottom: 1px solid #f1f5f9; border-right: 1px solid #cbd5e1;
    padding: 4px 4px; text-align: center; vertical-align: middle;
    overflow: visible !important;
}
.hem-tbl tr:hover .d-cell { background: rgba(0,0,0,0.02); }
.hem-tbl .d-cell.locked  { background: #f8fafc !important; min-width: 72px; }
.hem-locked-cell { display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 11px; height: 100%; }

/* Split cell (Jul/Jan, L/R) */
.hem-split  { display: flex; }
.hem-half   { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 4px 2px; gap: 2px; }
.hem-half + .hem-half { border-left: 1px solid #cbd5e1; }
.hem-sub-lbl { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
.hem-sub-lbl.amber { color: #f59e0b; }
.hem-sub-lbl.teal  { color: #0d9488; }

/* Inputs */
.hem-input {
    display: block; width: 100%; padding: 4px 2px;
    background: transparent; border: none; outline: none;
    text-align: center; font-size: 11.5px; font-family: inherit;
    color: #1e293b;
}
.hem-input:focus      { background: #eff6ff; border-radius: 4px; }
.hem-input::placeholder { color: #cbd5e1; }
.hem-input[type="number"] { -moz-appearance: textfield; }
.hem-input[type="number"]::-webkit-outer-spin-button,
.hem-input[type="number"]::-webkit-inner-spin-button { -webkit-appearance: none; }
.hem-input[type="date"]   { font-size: 10.5px; }

/* Selects */
.hem-select {
    display: block; width: 100%; padding: 4px 2px;
    background: transparent; border: none; outline: none;
    font-size: 11px; font-family: inherit; color: #374151; cursor: pointer;
}
.hem-select:focus { background: #eff6ff; border-radius: 4px; }

/* Checkboxes */
.hem-cb { width: 15px; height: 15px; accent-color: #1d4ed8; cursor: pointer; display: block; margin: 0 auto; }

/* Multi-select dropdown */
.hem-multi-wrapper { position: relative; display: block; }
.hem-multi-trigger {
    display: flex; flex-wrap: wrap; gap: 2px; align-items: center;
    min-height: 26px; padding: 2px 6px; cursor: pointer;
    border: 1px solid #cbd5e1; border-radius: 4px; background: white;
    font-size: 10px; width: 100%;
}
.hem-multi-trigger:hover { border-color: #94a3b8; }
.hem-multi-chip {
    display: inline-flex; align-items: center; gap: 2px;
    padding: 1px 4px; background: #e0e7ff; border-radius: 3px;
    color: #3730a3; font-size: 9px; font-weight: 600;
}
.hem-multi-chip button {
    background: none; border: none; cursor: pointer; padding: 0;
    color: #3730a3; font-size: 10px; line-height: 1;
}
.hem-multi-chip button:hover { color: #1d4ed8; }
.hem-multi-dropdown {
    position: fixed; z-index: 11000;
    min-width: 220px; max-height: 250px; overflow-y: auto;
    background: white; border: 1px solid #94a3b8;
    border-radius: 6px; box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    margin-top: 2px;
    padding: 4px 0;
}
.hem-multi-option {
    display: flex; align-items: center; gap: 8px; padding: 8px 12px;
    cursor: pointer; font-size: 12px; color: #374151; width: 100%;
}
.hem-multi-option:hover { background: #eff6ff; }
.hem-multi-option input { width: 16px; height: 16px; accent-color: #1d4ed8; }

/* Save row */
.hem-save-row td { background: #f8fafc; padding: 6px 4px; }
.hem-save-row .f-col { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #94a3b8; }
.hem-save-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 4px;
    width: 100%; padding: 5px 8px; font-size: 11px; font-weight: 600;
    border-radius: 6px; border: 1px solid #bfdbfe; background: #eff6ff;
    color: #1d4ed8; cursor: pointer; white-space: nowrap; font-family: inherit;
    transition: .15s;
}
.hem-save-btn:hover    { background: #dbeafe; border-color: #93c5fd; }
.hem-save-btn:disabled { opacity: .5; cursor: not-allowed; }
.hem-save-btn svg      { width: 11px; height: 11px; flex-shrink: 0; }

.hem-validate-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 4px;
    width: 100%; padding: 5px 8px; font-size: 11px; font-weight: 600;
    border-radius: 6px; border: 1px solid #86efac; background: #f0fdf4;
    color: #16a34a; cursor: pointer; white-space: nowrap; font-family: inherit;
    transition: .15s;
}
.hem-validate-btn:hover { background: #dcfce7; border-color: #4ade80; }
.hem-validated-badge {
    display: inline-flex; align-items: center; justify-content: center; gap: 4px;
    width: 100%; padding: 5px 8px; font-size: 11px; font-weight: 600;
    border-radius: 6px; border: 1px solid #fcd34d; background: #fef3c7;
    color: #92400e; cursor: default; white-space: nowrap; font-family: inherit;
}

/* Toast */
[x-cloak] { display: none !important; }
.hem-toast {
    position: fixed; bottom: 20px; right: 20px; z-index: 9999;
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 16px; background: #f0fdf4; border: 1px solid #86efac;
    border-radius: 8px; color: #15803d; font-size: 13px; font-weight: 500;
    box-shadow: 0 4px 16px rgba(0,0,0,.08);
}
.hem-toast svg { width: 14px; height: 14px; flex-shrink: 0; }
</style>

<div class="hem">
{{-- ═══ HEADER ═══ --}}
<div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:14px;">
    <div>
        <p class="hem-eyebrow">2019 SHD Form 1-B</p>
        <h2 class="hem-title">Medical / Nursing Findings</h2>
        <div class="hem-chips">
            <span class="hem-chip">
                <span>Student</span>
                <b>{{ $studentName }}</b>
            </span>
            @if ($studentGradeLevel)
                <span class="hem-chip hem-chip-blue">
                    <span>Current Grade</span>
                    <b>{{ $studentGradeLevel }}</b>
                </span>
            @endif
        </div>
    </div>
    <div style="padding-top:4px;">
        <button wire:click="toggleShowAll" class="hem-toggle {{ $showAll ? 'is-on' : '' }}">
            @if ($showAll)
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                Show Current Only
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Show All Grades
                @if ($hiddenCount > 0)
                    <span class="hem-badge-pill">+{{ $hiddenCount }}</span>
                @endif
            @endif
        </button>
    </div>
</div>

{{-- ═══ GRADE PIPS ═══ --}}
<div class="hem-pips">
    @foreach ($gradeLevels as $grade)
        @php $idx = $loop->index; @endphp
        <div class="hem-pip {{ $idx < $currentIdx ? 'past' : ($idx === $currentIdx ? 'curr' : '') }}" title="{{ $grade }}"></div>
    @endforeach
</div>

{{-- ═══ MATRIX ═══ --}}
<div class="hem-scroll" @scroll="$dispatch('scroll-matrix')">
<table class="hem-tbl">
<thead>
    <tr>
        <th class="f-col">Field</th>
        @foreach ($gradeLevels as $grade)
            @if ($this->isVisible($grade))
                @php
                    $gi     = array_search($grade, $gradeLevels);
                    $isCurr = $grade === $studentGradeLevel;
                    $isPast = $gi < $currentIdx;
                @endphp
                <th class="g-th {{ $isCurr ? 'curr' : ($isPast ? 'past' : '') }}"
                    style="background:{{ $cellBg($grade) }}"
                    wire:click="openModal('{{ $grade }}')">
                    {{ $grade }}
                    @if ($isCurr) <small>�-� now</small> @elseif ($isPast) <small>✓</small> @endif
                    @if (($data[$grade]['validated'] ?? false) && !($data[$grade]['reverted_at'] ?? null))
                        <span style="display:block;font-size:8px;color:#92400e;">✓VALIDATED</span>
                    @endif
                </th>
            @endif
        @endforeach
        @if (!$showAll && $hiddenCount > 0)
            <th class="th-hidden">
                <button class="hem-hidden-btn" wire:click="toggleShowAll">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    +{{ $hiddenCount }} hidden
                </button>
            </th>
        @endif
    </tr>
</thead>

<tbody>

{{-- ══ PHYSICAL MEASUREMENTS ══ --}}
<tr class="hem-sec s-blue"><td colspan="100">Physical Measurements</td></tr>

<tr>
    <td class="f-col">Date of Examination</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <input type="date" wire:model.defer="data.{{ $grade }}.date_of_examination" class="hem-input" />
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">Height (in cm)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <input type="number" step="0.01" min="0" placeholder="—" wire:model.defer="data.{{ $grade }}.height_cm" class="hem-input" />
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">Weight (in kg)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <input type="number" step="0.01" min="0" placeholder="—" wire:model.defer="data.{{ $grade }}.weight_kg" class="hem-input" />
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">NS (BMI/Wt-for-Age)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <select wire:model.defer="data.{{ $grade }}.ns_bmi_for_age" class="hem-select">
                @foreach ($legends['ns_bmi'] as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
            </select>
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">NS (Height-for-Age)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <select wire:model.defer="data.{{ $grade }}.ns_height_for_age" class="hem-select">
                @foreach ($legends['ns_height'] as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
            </select>
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

{{-- ══ INTERVENTIONS ══ --}}
<tr class="hem-sec s-green"><td colspan="100">Interventions</td></tr>

<tr>
    <td class="f-col">4Ps Beneficiary (√ or X)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <input type="checkbox" wire:model.defer="data.{{ $grade }}.is_4ps_beneficiary" class="hem-cb" />
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">SBFP Beneficiary (√ or X)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <input type="checkbox" wire:model.defer="data.{{ $grade }}.is_sbfp_beneficiary" class="hem-cb" />
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

{{-- Deworming — Jul & Jan in one split cell --}}
<tr>
    <td class="f-col">Deworming (√ or X)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="padding:0; background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <div class="hem-split">
                <div class="hem-half">
                    <span class="hem-sub-lbl amber">Jul</span>
                    <input type="checkbox" wire:model.defer="data.{{ $grade }}.deworming_july" class="hem-cb" />
                </div>
                <div class="hem-half">
                    <span class="hem-sub-lbl amber">Jan</span>
                    <input type="checkbox" wire:model.defer="data.{{ $grade }}.deworming_january" class="hem-cb" />
                </div>
            </div>
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">Iron Supplementation (√ or X)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <input type="checkbox" wire:model.defer="data.{{ $grade }}.iron_supplementation" class="hem-cb" />
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">Immunization (Specify)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <input type="text" wire:model.defer="data.{{ $grade }}.immunization_kind" placeholder="—" class="hem-input" />
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

{{-- ══ VITALS ══ --}}
<tr class="hem-sec s-violet"><td colspan="100">Vitals</td></tr>

@foreach ([
    ['key' => 'menarche',        'label' => 'Menarche'],
    ['key' => 'temperature',     'label' => 'Temperature'],
    ['key' => 'blood_pressure',  'label' => 'Blood Pressure'],
    ['key' => 'pulse_rate',      'label' => 'Pulse Rate'],
    ['key' => 'respiratory_rate','label' => 'Respiratory Rate'],
] as $f)
<tr>
    <td class="f-col">{{ $f['label'] }}</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <input type="text" wire:model.defer="data.{{ $grade }}.{{ $f['key'] }}" placeholder="—" class="hem-input" />
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>
@endforeach

{{-- ══ SCREENINGS ══ --}}
<tr class="hem-sec s-teal"><td colspan="100">Vision / Auditory Screening</td></tr>

<tr>
    <td class="f-col">Vision Screening</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="padding:0; background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <div class="hem-split">
                <div class="hem-half">
                    <span class="hem-sub-lbl teal">L</span>
                    <select wire:model.defer="data.{{ $grade }}.vision_l" class="hem-select" style="font-size:10px;">
                        @foreach ($legends['screenings'] as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
                    </select>
                </div>
                <div class="hem-half">
                    <span class="hem-sub-lbl teal">R</span>
                    <select wire:model.defer="data.{{ $grade }}.vision_r" class="hem-select" style="font-size:10px;">
                        @foreach ($legends['screenings'] as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
                    </select>
                </div>
            </div>
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">Auditory Screening</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="padding:0; background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <div class="hem-split">
                <div class="hem-half">
                    <span class="hem-sub-lbl teal">L</span>
                    <select wire:model.defer="data.{{ $grade }}.auditory_l" class="hem-select" style="font-size:10px;">
                        @foreach ($legends['screenings'] as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
                    </select>
                </div>
                <div class="hem-half">
                    <span class="hem-sub-lbl teal">R</span>
                    <select wire:model.defer="data.{{ $grade }}.auditory_r" class="hem-select" style="font-size:10px;">
                        @foreach ($legends['screenings'] as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
                    </select>
                </div>
            </div>
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

{{-- ══ EXAMINATION FINDINGS ══ --}}
<tr class="hem-sec s-orange"><td colspan="100">Examination Findings</td></tr>

@foreach ([
    ['key' => 'skin_scalp',       'label' => 'Skin / Scalp',          'legend' => 'skin_scalp'],
    ['key' => 'eyes_ears_nose',   'label' => 'Eyes / Ears / Nose',    'legend' => 'eyes_ears_nose'],
    ['key' => 'mouth_neck_throat','label' => 'Mouth / Throat / Neck', 'legend' => 'mouth_neck_throat'],
    ['key' => 'lungs_heart',      'label' => 'Lungs / Heart',         'legend' => 'lungs_heart'],
    ['key' => 'abdomen',          'label' => 'Abdomen',               'legend' => 'abdomen'],
    ['key' => 'deformities',      'label' => 'Deformities',           'legend' => 'deformities'],
] as $f)
<tr>
    <td class="f-col">{{ $f['label'] }}</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}; padding: 2px;">
            @if($this->canSave($grade))
            @php($fieldKey = $f['key'])
            @php($currentValues = $data[$grade][$fieldKey] ?? [])
            <div class="hem-multi-wrapper" 
                 wire:key="multi-{{ $grade }}-{{ $fieldKey }}"
                 x-data="{ 
                     id: '{{ $grade }}-{{ $fieldKey }}',
                     opts: @js(array_keys($legends[$f['legend']])),
                     labels: @js($legends[$f['legend']]),
                     vals: @js($currentValues),
                     get isOpen() { return openMultiSelect === this.id },
                     toggle() { 
                         if (this.isOpen) {
                             openMultiSelect = null;
                         } else {
                             openMultiSelect = this.id;
                             this.reposition();
                         }
                     },
                     reposition() {
                         this.$nextTick(() => {
                            const el = this.$el.querySelector('.hem-multi-dropdown');
                            const trigger = this.$el.querySelector('.hem-multi-trigger');
                            if(!el || !trigger) return;
                            const rect = trigger.getBoundingClientRect();
                            el.style.top = (rect.bottom + 4) + 'px';
                            el.style.left = rect.left + 'px';
                         });
                     },
                     isSelected(v) { return this.vals.includes(v) },
                     toggleOpt(v) {
                         if(this.vals.includes(v)) {
                             this.vals = this.vals.filter(x => x !== v);
                         } else {
                             this.vals.push(v);
                         }
                         $wire.set('data.{{ $grade }}.{{ $fieldKey }}', this.vals);
                     },
                     resetAll() {
                         this.vals = [];
                         $wire.set('data.{{ $grade }}.{{ $fieldKey }}', []);
                     }
                 }" 
                 x-init="$watch('openMultiSelect', value => { if(value === id) reposition() })"
                 @click.outside="if(isOpen) openMultiSelect = null"
                 x-on:scroll.window.passive="if(isOpen) reposition()"
                 @scroll-matrix.window="if(isOpen) reposition()">
                <div class="hem-multi-trigger" @click.stop="toggle()">
                    @forelse($currentValues as $val)
                        <span class="hem-multi-chip">{{ $legends[$f['legend']][$val] ?? $val }}</span>
                    @empty
                        <span style="color:#94a3b8;font-size:9px;">Select...</span>
                    @endforelse
                </div>
                <div x-show="isOpen" wire:ignore x-transition x-cloak class="hem-multi-dropdown" style="display:none;position:fixed;z-index:11000;min-width:200px;max-height:250px;overflow-y:auto;background:white;border:1px solid #94a3b8;border-radius:6px;box-shadow:0 8px 20px rgba(0,0,0,0.2);padding:4px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:4px 8px;border-bottom:1px solid #f1f5f9;margin-bottom:4px;position:sticky;top:0;background:white;z-index:10;">
                        <span style="font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;">Options</span>
                        <button @click.stop="resetAll()" style="font-size:10px;color:#ef4444;background:none;border:none;cursor:pointer;font-weight:700;padding:2px 4px;border-radius:3px;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='none'">Reset</button>
                    </div>
                    
                    <div style="padding-bottom:40px;"> {{-- extra padding for sticky footer --}}
                        @foreach($legends[$f['key']] as $optVal => $optLabel)
                            <label style="display:flex;align-items:center;gap:8px;padding:6px 8px;cursor:pointer;font-size:11px;" 
                                   @mouseenter="$el.style.background='#eff6ff'" 
                                   @mouseleave="$el.style.background='white'">
                                <input type="checkbox" 
                                       {{ in_array($optVal, $currentValues) ? 'checked' : '' }} 
                                       @click.stop="toggleOpt('{{ $optVal }}')"
                                       style="width:14px;height:14px;accent-color:#1d4ed8;">
                                <span>{{ $optLabel }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div style="position:sticky;bottom:0;background:white;padding:6px;border-top:1px solid #f1f5f9;display:flex;justify-content:center;">
                        <button @click.stop="openMultiSelect = null" 
                                style="width:100%;background:#1d4ed8;color:white;border:none;border-radius:4px;padding:6px;font-size:11px;font-weight:700;cursor:pointer;"
                                onmouseover="this.style.background='#1e40af'"
                                onmouseout="this.style.background='#1d4ed8'">
                            Done
                        </button>
                    </div>
                </div>
            </div>
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>
@endforeach

{{-- ══ OTHERS ══ --}}
<tr class="hem-sec s-gray"><td colspan="100">Others</td></tr>

<tr>
    <td class="f-col">Others, specify</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <input type="text" wire:model.defer="data.{{ $grade }}.others_specify" placeholder="—" class="hem-input" />
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">Examined By</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <input type="text" wire:model.defer="data.{{ $grade }}.examined_by_name" placeholder="—" class="hem-input" style="font-size:10px;" />
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">Designation</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            @if($this->canSave($grade))
            <input type="text" wire:model.defer="data.{{ $grade }}.designation" placeholder="—" class="hem-input" style="font-size:10px;" />
            @else
            <div class="hem-locked-cell">—</div>
            @endif
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

{{-- ══ SAVE ROW ══ --}}
<tr class="hem-save-row">
    <td class="f-col">Action</td>
    @foreach ($gradeLevels as $grade)
        @if ($this->isVisible($grade))
        <td style="padding:4px 4px; background:#f8fafc; border-right:1px solid #f1f5f9;">
            <div style="display:flex;flex-direction:column;gap:4px;">
                @if (!$this->canSave($grade))
                <div style="display:flex;align-items:center;justify-content:center;gap:4px;padding:5px 8px;background:#fef3c7;border:1px solid #fcd34d;border-radius:6px;color:#92400e;font-size:11px;font-weight:600;">
                    <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    Locked
                </div>
                @else
                <button
                    wire:click="performSave({{ $loop->index }})"
                    wire:loading.attr="disabled"
                    wire:target="performSave({{ $loop->index }})"
                    class="hem-save-btn">
                    <span wire:loading.remove wire:target="performSave({{ $loop->index }})">Save</span>
                    <span wire:loading wire:target="performSave({{ $loop->index }})">Saving...</span>
                </button>
                @endif

                @if ($this->isAdmin())
                    @if (($data[$grade]['validated'] ?? false) && !($data[$grade]['reverted_at'] ?? null))
                    <button wire:click="setGradeForInvalidate('{{ $grade }}')" class="hem-validate-btn" style="background:#fef3c7;border-color:#fcd34d;color:#92400e;">
                        Invalidate
                    </button>
                    @elseif (!($data[$grade]['validated'] ?? false))
                    <button wire:click="setGradeForValidate('{{ $grade }}')" wire:loading.attr="disabled" wire:target="setGradeForValidate('{{ $grade }}')" class="hem-validate-btn">
                        <span wire:loading.remove wire:target="setGradeForValidate('{{ $grade }}')">Validate</span>
                        <span wire:loading wire:target="setGradeForValidate('{{ $grade }}')">Validating...</span>
                    </button>
                    @endif
                @else
                    @if (!($data[$grade]['validated'] ?? false))
                    <button wire:click="setGradeForValidate('{{ $grade }}')" class="hem-validate-btn">
                        Validate
                    </button>
                    @endif
                @endif
            </div>
        </td>
        @endif
    @endforeach
    @if (!$showAll && $hiddenCount > 0)<td style="background:#f8fafc;"></td>@endif
</tr>


</tbody>
</table>
</div>

{{-- ══ TOAST ══ --}}
<div x-show="toastShow" x-transition x-cloak class="hem-toast">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
    <span x-text="toastGrade + ' saved successfully'"></span>
</div>

{{-- VALIDATION CONFIRMATION POPUP --}}
@if ($pendingValidationGrade)
<div style="position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:white;border-radius:0.75rem;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);padding:1.5rem;width:100%;max-width:24rem;border:4px solid #f59e0b;">
        <div style="text-align:center;margin-bottom:1rem;">
            <div style="width:3rem;height:3rem;background:#fef3c7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
                <svg style="width:1.5rem;height:1.5rem;color:#92400e;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 style="font-size:1.125rem;font-weight:700;color:#111827;">Confirm Validation</h3>
        </div>
        <p style="font-size:0.875rem;color:#374151;text-align:center;margin-bottom:0.5rem;">Validate entry for <strong>{{ $pendingValidationGrade }}</strong>?</p>
        <p style="font-size:0.75rem;color:#78350f;text-align:center;margin-bottom:1rem;padding:0.5rem;background:#fef3c7;border-radius:0.25rem;">⚠️ Once validated, only admins can edit or invalidate this entry.</p>
        <div style="display:flex;gap:0.5rem;">
            <button wire:click="confirmValidate()" style="flex:1;background:#16a34a;color:white;padding:0.5rem 1rem;border-radius:0.5rem;font-weight:600;border:none;">Yes, Validate</button>
            <button wire:click="cancelValidate()" style="flex:1;background:#ef4444;color:white;padding:0.5rem 1rem;border-radius:0.5rem;font-weight:600;border:none;">Cancel</button>
        </div>
    </div>
</div>
@endif

{{-- ══ MODAL FOR EDITING ══ --}}
@if ($isModalOpen && $selectedGrade)
<div style="position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.5);display:flex;align-items:flex-start;justify-content:center;padding:2rem;overflow-y:auto;">
    <div style="background:white;border-radius:0.75rem;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);padding:1.5rem;width:100%;max-width:48rem;margin:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <h3 style="font-size:1.125rem;font-weight:700;color:#111827;">
                Edit: {{ $selectedGrade }}
                @if (($data[$selectedGrade]['validated'] ?? false) && !($data[$selectedGrade]['reverted_at'] ?? null))
                    <span style="display:inline-flex;align-items:center;padding:2px 6px;background:#fef3c7;color:#92400e;border-radius:4px;font-size:9px;font-weight:700;margin-left:8px;">VALIDATED</span>
                @endif
            </h3>
            <button wire:click="closeModal()" style="color:#9ca3af;background:none;border:none;cursor:pointer;padding:4px;">
                <svg style="width:1.5rem;height:1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <div style="max-height:60vh;overflow-y:auto;">
            @if($this->canSave($selectedGrade))
            {{-- EXAMINATION INFO --}}
            <div style="background:#f8fafc;padding:0.5rem;border-radius:0.25rem;margin-bottom:1rem;">
                <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:#64748b;">Examination Info</span>
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem;margin-bottom:1rem;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Date of Exam</label>
                    <input type="date" wire:model.defer="data.{{ $selectedGrade }}.date_of_examination" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;" />
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Designation</label>
                    <input type="text" wire:model.defer="data.{{ $selectedGrade }}.designation" placeholder="School Nurse, etc." style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;" />
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Examined By</label>
                    <input type="text" wire:model.defer="data.{{ $selectedGrade }}.examined_by_name" placeholder="Enter name" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;" />
                </div>
            </div>

            {{-- PHYSICAL MEASUREMENTS --}}
            <div style="background:#eff6ff;padding:0.5rem;border-radius:0.25rem;margin-bottom:1rem;">
                <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:#1d4ed8;">Physical Measurements</span>
            </div>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:0.75rem;margin-bottom:1rem;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Height (cm)</label>
                    <input type="number" step="0.01" wire:model.defer="data.{{ $selectedGrade }}.height_cm" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;" />
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Weight (kg)</label>
                    <input type="number" step="0.01" wire:model.defer="data.{{ $selectedGrade }}.weight_kg" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;" />
                </div>
            </div>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:0.75rem;margin-bottom:1rem;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">NS (BMI/Wt-for-Age)</label>
                    <select wire:model.defer="data.{{ $selectedGrade }}.ns_bmi_for_age" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;">
                        <option value="">—</option>
                        <option value="severely_underweight">Severely Underweight</option>
                        <option value="underweight">Underweight</option>
                        <option value="normal">Normal</option>
                        <option value="overweight">Overweight</option>
                        <option value="obese">Obese</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">NS (Ht-for-Age)</label>
                    <select wire:model.defer="data.{{ $selectedGrade }}.ns_height_for_age" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;">
                        <option value="">—</option>
                        <option value="stunted">Stunted</option>
                        <option value="normal">Normal</option>
                    </select>
                </div>
            </div>

            {{-- INTERVENTIONS --}}
            <div style="background:#f0fdf4;padding:0.5rem;border-radius:0.25rem;margin-bottom:1rem;margin-top:1rem;">
                <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:#15803d;">Interventions</span>
            </div>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.5rem;margin-bottom:1rem;">
                <div style="display:flex;align-items:center;gap:0.25rem;">
                    <input type="checkbox" wire:model.defer="data.{{ $selectedGrade }}.is_4ps_beneficiary" style="width:1rem;height:1rem;" />
                    <label style="font-size:0.75rem;">4Ps</label>
                </div>
                <div style="display:flex;align-items:center;gap:0.25rem;">
                    <input type="checkbox" wire:model.defer="data.{{ $selectedGrade }}.is_sbfp_beneficiary" style="width:1rem;height:1rem;" />
                    <label style="font-size:0.75rem;">SBFP</label>
                </div>
                <div style="display:flex;align-items:center;gap:0.25rem;">
                    <input type="checkbox" wire:model.defer="data.{{ $selectedGrade }}.deworming_july" style="width:1rem;height:1rem;" />
                    <label style="font-size:0.75rem;">Deworm Jul</label>
                </div>
                <div style="display:flex;align-items:center;gap:0.25rem;">
                    <input type="checkbox" wire:model.defer="data.{{ $selectedGrade }}.deworming_january" style="width:1rem;height:1rem;" />
                    <label style="font-size:0.75rem;">Deworm Jan</label>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:0.75rem;margin-bottom:1rem;">
                <div style="display:flex;align-items:center;gap:0.25rem;">
                    <input type="checkbox" wire:model.defer="data.{{ $selectedGrade }}.iron_supplementation" style="width:1rem;height:1rem;" />
                    <label style="font-size:0.75rem;">Iron Supplement</label>
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Immunization</label>
                    <input type="text" wire:model.defer="data.{{ $selectedGrade }}.immunization_kind" placeholder="Specify..." style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;" />
                </div>
            </div>

            {{-- VITALS --}}
            <div style="background:#f5f3ff;padding:0.5rem;border-radius:0.25rem;margin-bottom:1rem;margin-top:1rem;">
                <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:#6d28d9;">Vitals</span>
            </div>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.5rem;margin-bottom:1rem;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;">Menarche</label>
                    <input type="text" wire:model.defer="data.{{ $selectedGrade }}.menarche" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;" />
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;">Temp</label>
                    <input type="text" wire:model.defer="data.{{ $selectedGrade }}.temperature" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;" />
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;">BP</label>
                    <input type="text" wire:model.defer="data.{{ $selectedGrade }}.blood_pressure" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;" />
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;">Pulse</label>
                    <input type="text" wire:model.defer="data.{{ $selectedGrade }}.pulse_rate" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;" />
                </div>
            </div>

            {{-- SCREENINGS --}}
            <div style="background:#f0fdfa;padding:0.5rem;border-radius:0.25rem;margin-bottom:1rem;margin-top:1rem;">
                <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:#0f766e;">Vision/Auditory</span>
            </div>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.5rem;margin-bottom:1rem;">
                <div>
                    <label style="display:block;font-size:0.7rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Vision L</label>
                    <select wire:model.defer="data.{{ $selectedGrade }}.vision_l" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;">
                        <option value="">—</option>
                        <option value="normal">Normal</option>
                        <option value="needs_referral">Needs Referral</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.7rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Vision R</label>
                    <select wire:model.defer="data.{{ $selectedGrade }}.vision_r" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;">
                        <option value="">—</option>
                        <option value="normal">Normal</option>
                        <option value="needs_referral">Needs Referral</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.7rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Hearing L</label>
                    <select wire:model.defer="data.{{ $selectedGrade }}.auditory_l" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;">
                        <option value="">—</option>
                        <option value="normal">Normal</option>
                        <option value="needs_referral">Needs Referral</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.7rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Hearing R</label>
                    <select wire:model.defer="data.{{ $selectedGrade }}.auditory_r" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;">
                        <option value="">—</option>
                        <option value="normal">Normal</option>
                        <option value="needs_referral">Needs Referral</option>
                    </select>
                </div>
            </div>

            {{-- FINDINGS --}}
            <div style="background:#fff7ed;padding:0.5rem;border-radius:0.25rem;margin-bottom:1rem;margin-top:1rem;">
                <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:#c2410c;">Examination Findings</span>
            </div>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:0.5rem;margin-bottom:1rem;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;">Skin/Scalp</label>
                    <select wire:model.defer="data.{{ $selectedGrade }}.skin_scalp" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;">
                        <option value="">—</option>
                        <option value="normal">Normal</option>
                        <option value="abnormal">Abnormal</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;">Eyes/Ears/Nose</label>
                    <select wire:model.defer="data.{{ $selectedGrade }}.eyes_ears_nose" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;">
                        <option value="">—</option>
                        <option value="normal">Normal</option>
                        <option value="abnormal">Abnormal</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;">Mouth/Throat</label>
                    <select wire:model.defer="data.{{ $selectedGrade }}.mouth_neck_throat" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;">
                        <option value="">—</option>
                        <option value="normal">Normal</option>
                        <option value="abnormal">Abnormal</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;">Lungs/Heart</label>
                    <select wire:model.defer="data.{{ $selectedGrade }}.lungs_heart" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;">
                        <option value="">—</option>
                        <option value="normal">Normal</option>
                        <option value="abnormal">Abnormal</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;">Abdomen</label>
                    <select wire:model.defer="data.{{ $selectedGrade }}.abdomen" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;">
                        <option value="">—</option>
                        <option value="normal">Normal</option>
                        <option value="abnormal">Abnormal</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;">Deformities</label>
                    <select wire:model.defer="data.{{ $selectedGrade }}.deformities" style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;">
                        <option value="">—</option>
                        <option value="none">None</option>
                        <option value="present">Present</option>
                    </select>
                </div>
            </div>

            <div>
                <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">Others, specify</label>
                <input type="text" wire:model.defer="data.{{ $selectedGrade }}.others_specify" placeholder="Other findings..." style="width:100%;padding:0.375rem;border:1px solid #d1d5db;border-radius:0.25rem;font-size:0.75rem;" />
            </div>
            @endif
        </div>

        <div style="display:flex;gap:0.75rem;margin-top:1.5rem;padding-top:1rem;border-top:1px solid #e5e7eb;">
            @if (($data[$selectedGrade]['validated'] ?? false) && !($data[$selectedGrade]['reverted_at'] ?? null) && !$this->isAdmin())
            <div style="flex:1;display:flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.5rem;background:#fef3c7;border:1px solid #fcd34d;border-radius:0.5rem;color:#92400e;font-size:0.875rem;font-weight:600;">
                <svg style="width:1.25rem;height:1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                Locked - Contact Admin to Edit
            </div>
            @else
            <button 
                wire:click="performSaveByGrade('{{ $selectedGrade }}')" 
                style="flex:1;background:#2563eb;color:white;padding:0.5rem 1rem;border-radius:0.5rem;font-weight:500;font-size:0.875rem;cursor:pointer;border:none;"
            >
                Save
            </button>
            @endif
            @if ($this->isAdmin())
                @if (($data[$selectedGrade]['validated'] ?? false) && !($data[$selectedGrade]['reverted_at'] ?? null))
                <button wire:click="setGradeForInvalidate('{{ $selectedGrade }}')" style="background:#f59e0b;color:white;padding:0.5rem 1rem;border-radius:0.5rem;font-weight:500;font-size:0.875rem;cursor:pointer;border:none;">
                    Invalidate
                </button>
                @elseif (!($data[$selectedGrade]['validated'] ?? false))
                <button wire:click="setGradeForValidate('{{ $selectedGrade }}')" style="background:#16a34a;color:white;padding:0.5rem 1rem;border-radius:0.5rem;font-weight:500;font-size:0.875rem;cursor:pointer;border:none;">
                    Validate
                </button>
                @endif
            @else
                @if (!($data[$selectedGrade]['validated'] ?? false))
                <button wire:click="setGradeForValidate('{{ $selectedGrade }}')" style="background:#16a34a;color:white;padding:0.5rem 1rem;border-radius:0.5rem;font-weight:500;font-size:0.875rem;cursor:pointer;border:none;">
                    Validate
                </button>
                @endif
            @endif
        </div>
    </div>
</div>
@endif

</div>

</div>{{-- end single root --}}
