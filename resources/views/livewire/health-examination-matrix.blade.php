{{-- resources/views/livewire/health-examination-matrix.blade.php --}}

@php
    $hiddenCount = count(array_filter($gradeLevels, fn($g) => !$this->isVisible($g)));
    $currentIdx  = $studentGradeLevel ? array_search($studentGradeLevel, $gradeLevels) : 0;

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
    x-data="{ toastShow: false, toastGrade: '' }"
    x-on:saved.window="toastShow = true; toastGrade = $event.detail.grade; setTimeout(() => toastShow = false, 2500)"
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
    border-right: 1px solid #e2e8f0;
    font-size: 12px; font-weight: 500; color: #374151;
    white-space: nowrap;
}
.hem-tbl thead .f-col {
    background: #f8fafc; font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .07em; color: #64748b;
    border-bottom: 2px solid #e2e8f0; padding: 10px 14px; z-index: 3;
}
.hem-tbl tr:hover .f-col { background: #f8fafc; }

/* Grade header cells */
.hem-tbl .g-th {
    min-width: 100px; padding: 8px 6px 6px;
    text-align: center; font-size: 11.5px; font-weight: 600;
    color: #94a3b8; background: #f8fafc;
    border-bottom: 2px solid #e2e8f0; border-right: 1px solid #f1f5f9;
    white-space: nowrap; line-height: 1.3;
}
.hem-tbl .g-th.curr  { background: #eff6ff; border-bottom-color: #1d4ed8; color: #1d4ed8; }
.hem-tbl .g-th.past  { color: #94a3b8; }
.hem-tbl .g-th small { display: block; font-size: 9px; margin-top: 2px; }
.hem-tbl .g-th.curr small { color: #1d4ed8; font-weight: 700; }
.hem-tbl .g-th.past small { color: #93c5fd; }

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
    border-bottom: 1px solid #f1f5f9; border-right: 1px solid #f1f5f9;
    padding: 4px 4px; text-align: center; vertical-align: middle;
}
.hem-tbl tr:hover .d-cell { filter: brightness(.97); }
.hem-tbl .d-cell.locked  { background: #f8fafc !important; min-width: 72px; }

/* Split cell (Jul/Jan, L/R) */
.hem-split  { display: flex; }
.hem-half   { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 4px 2px; gap: 2px; }
.hem-half + .hem-half { border-left: 1px solid #f1f5f9; }
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
<div class="hem-scroll">
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
                    style="background:{{ $cellBg($grade) }}">
                    {{ $grade }}
                    @if ($isCurr) <small>● now</small> @elseif ($isPast) <small>✓</small> @endif
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
            <input type="date" wire:model.defer="data.{{ $grade }}.date_of_examination" class="hem-input" />
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">Height (in cm)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            <input type="number" step="0.01" min="0" placeholder="—" wire:model.defer="data.{{ $grade }}.height_cm" class="hem-input" />
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">Weight (in kg)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            <input type="number" step="0.01" min="0" placeholder="—" wire:model.defer="data.{{ $grade }}.weight_kg" class="hem-input" />
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">NS (BMI/Wt-for-Age)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            <select wire:model.defer="data.{{ $grade }}.ns_bmi_for_age" class="hem-select">
                @foreach ($legends['ns_bmi'] as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
            </select>
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">NS (Height-for-Age)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            <select wire:model.defer="data.{{ $grade }}.ns_height_for_age" class="hem-select">
                @foreach ($legends['ns_height'] as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
            </select>
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
            <input type="checkbox" wire:model.defer="data.{{ $grade }}.is_4ps_beneficiary" class="hem-cb" />
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">SBFP Beneficiary (√ or X)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            <input type="checkbox" wire:model.defer="data.{{ $grade }}.is_sbfp_beneficiary" class="hem-cb" />
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

{{-- Deworming — Jul & Jan in one split cell --}}
<tr>
    <td class="f-col">Deworming (√ or X)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="padding:0; background:{{ $cellBg($grade) }}">
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
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">Iron Supplementation (√ or X)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            <input type="checkbox" wire:model.defer="data.{{ $grade }}.iron_supplementation" class="hem-cb" />
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">Immunization (Specify)</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            <input type="text" wire:model.defer="data.{{ $grade }}.immunization_kind" placeholder="—" class="hem-input" />
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
            <input type="text" wire:model.defer="data.{{ $grade }}.{{ $f['key'] }}" placeholder="—" class="hem-input" />
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
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

<tr>
    <td class="f-col">Auditory Screening</td>
    @foreach ($gradeLevels as $grade) @if ($this->isVisible($grade))
        <td class="d-cell" style="padding:0; background:{{ $cellBg($grade) }}">
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
        <td class="d-cell" style="background:{{ $cellBg($grade) }}">
            <select wire:model.defer="data.{{ $grade }}.{{ $f['key'] }}" class="hem-select">
                @foreach ($legends[$f['legend']] as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
            </select>
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
            <input type="text" wire:model.defer="data.{{ $grade }}.others_specify" placeholder="—" class="hem-input" />
        </td>
    @endif @endforeach
    @if (!$showAll && $hiddenCount > 0)<td class="d-cell locked"></td>@endif
</tr>

{{-- ══ SAVE ROW ══ --}}
<tr class="hem-save-row">
    <td class="f-col">Action</td>
    @foreach ($gradeLevels as $grade)
        @php $gradeIndex = $loop->index; @endphp
        @if ($this->isVisible($grade))
        <td style="padding:6px 4px; background:#f8fafc; border-right:1px solid #f1f5f9;">
            <button
                wire:click="performSave({{ $gradeIndex }})"
                wire:loading.attr="disabled"
                wire:target="performSave({{ $gradeIndex }})"
                class="hem-save-btn">
                <span wire:loading.remove wire:target="performSave({{ $gradeIndex }})">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" style="width:11px;height:11px;display:inline;vertical-align:middle;margin-right:2px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Save
                </span>
                <span wire:loading wire:target="performSave({{ $gradeIndex }})">Saving…</span>
            </button>
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

</div>

</div>{{-- end single root --}}