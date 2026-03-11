<?php

namespace App\Filament\Resources\HealthRecords\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class HealthRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->relationship('student', 'id')
                    ->required(),
                DatePicker::make('record_date')
                    ->required(),
                TextInput::make('height_cm')
                    ->numeric(),
                TextInput::make('weight_kg')
                    ->numeric(),
                TextInput::make('bmi')
                    ->numeric(),
                TextInput::make('bmi_category'),
                Textarea::make('medical_conditions')
                    ->columnSpanFull(),
                Textarea::make('allergies')
                    ->columnSpanFull(),
                Textarea::make('medications')
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('recorded_by')
                    ->required()
                    ->numeric(),
            ]);
    }
}
