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
                    ->relationship('school', 'name')
                    ->required(),
                TextInput::make('clinic_name')
                    ->required(),
                TextInput::make('location')
                    ->required(),
                TextInput::make('head_nurse_name'),
                TextInput::make('nurse_contact'),
                TextInput::make('bed_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('equipment_inventory')
                    ->columnSpanFull(),
                Textarea::make('operating_hours')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
