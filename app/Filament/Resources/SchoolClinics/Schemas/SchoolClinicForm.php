<?php

namespace App\Filament\Resources\SchoolClinics\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SchoolClinicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('school_id')
                    ->label('School')
                    ->relationship('school', 'name')
                    ->required(),
                TextInput::make('clinic_name')
                    ->label('Clinic Name')
                    ->required(),
                TextInput::make('location')
                    ->label('Location')
                    ->required(),
                TextInput::make('head_nurse_name')
                    ->label('Head Nurse Name'),
                TextInput::make('nurse_contact')
                    ->label('Nurse Contact'),
                TextInput::make('bed_count')
                    ->label('Bed Count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('equipment_inventory')
                    ->label('Equipment Inventory')
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('operating_hours')
                    ->label('Operating Hours')
                    ->rows(3)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->helperText('Enable if this clinic is currently operational.')
                    ->default(true)
                    ->required(),
            ]);
    }
}
