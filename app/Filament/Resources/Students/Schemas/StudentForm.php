<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('school_id')
                    ->relationship('school', 'name')
                    ->required(),
                TextInput::make('lrn')
                    ->required(),
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('middle_name'),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('suffix'),
                DatePicker::make('birth_date')
                    ->required(),
                Select::make('sex')
                    ->options(['male' => 'Male', 'female' => 'Female'])
                    ->required(),
                TextInput::make('address')
                    ->required(),
                TextInput::make('guardian_name')
                    ->required(),
                TextInput::make('guardian_contact')
                    ->required(),
                TextInput::make('guardian_relationship')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
