<?php

namespace App\Filament\Resources\Absences\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AbsenceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->relationship('student', 'first_name', fn ($query) => auth()->user()->hasRole('health_coordinator') ? $query->where('school_id', auth()->user()->school_id) : $query)
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name} ({$record->lrn})")
                    ->searchable(['first_name', 'last_name', 'lrn'])
                    ->preload()
                    ->required(),
                DatePicker::make('absence_date')
                    ->required(),
                TextInput::make('reason')
                    ->required(),
                Textarea::make('diagnosis')
                    ->columnSpanFull(),
                Toggle::make('is_health_related')
                    ->required(),
                TextInput::make('days_absent')
                    ->required()
                    ->numeric()
                    ->default(1),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('recorded_by')
                    ->required()
                    ->numeric(),
            ]);
    }
}
