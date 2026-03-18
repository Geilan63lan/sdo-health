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
                    ->numeric()
                    ->suffix('cm')
                    ->live(debounce: 500)
                    ->afterStateUpdated(function ($state, $set, $get) {
                        self::updateBMI($set, $get, $state, $get('weight_kg'));
                    }),
                TextInput::make('weight_kg')
                    ->numeric()
                    ->suffix('kg')
                    ->live(debounce: 500)
                    ->afterStateUpdated(function ($state, $set, $get) {
                        self::updateBMI($set, $get, $get('height_cm'), $state);
                    }),
                TextInput::make('bmi')
                    ->numeric()
                    ->step(0.1)
                    ->helperText('Auto-calculated from height and weight (editable if needed)'),
                TextInput::make('bmi_category')
                    ->helperText('Underweight: < 18.5 | Normal: 18.5-24.9 | Overweight: 25-29.9 | Obese: ≥ 30'),
                Textarea::make('medical_conditions')
                    ->columnSpanFull(),
                Textarea::make('allergies')
                    ->columnSpanFull(),
                Textarea::make('medications')
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                Select::make('recorded_by')
                    ->relationship('recordedBy', 'name')
                    ->required(),
            ]);
    }

    private static function updateBMI($set, $get, $height, $weight)
    {
        $height = $height ?? $get('height_cm');
        $weight = $weight ?? $get('weight_kg');

        if ($height && $weight && $height > 0) {
            $heightInMeters = $height / 100;
            $bmi = $weight / ($heightInMeters ** 2);
            $bmi = round($bmi, 1);

            $category = match (true) {
                $bmi < 18.5 => 'Underweight',
                $bmi >= 18.5 && $bmi < 25 => 'Normal',
                $bmi >= 25 && $bmi < 30 => 'Overweight',
                $bmi >= 30 => 'Obese',
                default => '',
            };

            $set('bmi', $bmi);
            $set('bmi_category', $category);
        }
    }
}
