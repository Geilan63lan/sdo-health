<?php

namespace App\Filament\Widgets;

use App\Models\HealthRecord;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RecentHealthRecords extends TableWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $user = Auth::user();
        $isSdoAdmin = $user->role === 'sdo_admin';
        $schoolId = $user->school_id;

        return $table
            ->query(
                HealthRecord::query()
                    ->latest()
                    ->when(!$isSdoAdmin && $schoolId, function (Builder $query) use ($schoolId) {
                        $query->whereHas('student', fn ($q) => $q->where('school_id', $schoolId));
                    })
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('student.full_name')
                    ->label('Student')
                    ->searchable(),
                TextColumn::make('bmi')
                    ->label('BMI')
                    ->numeric(1),
                TextColumn::make('bmi_category')
                    ->label('Category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Underweight' => 'warning',
                        'Normal' => 'success',
                        'Overweight' => 'danger',
                        'Obese' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('record_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('recordedBy.name')
                    ->label('Recorded By'),
            ]);
    }
}
