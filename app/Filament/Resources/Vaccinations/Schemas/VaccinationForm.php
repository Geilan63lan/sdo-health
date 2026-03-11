<?php

namespace App\Filament\Resources\Vaccinations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VaccinationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->relationship('student', 'id')
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
