<?php

namespace App\Filament\Resources\Vaccinations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VaccinationForm
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
                TextInput::make('vaccine_name')
                    ->required(),
                DatePicker::make('date_given')
                    ->required(),
                TextInput::make('dose_number'),
                TextInput::make('administered_by'),
                TextInput::make('batch_number'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('recorded_by')
                    ->required()
                    ->numeric(),
            ]);
    }
}
