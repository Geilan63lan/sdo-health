<?php

namespace App\Filament\Resources\Schools\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SchoolForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('address')
                    ->required(),
                Select::make('level')
                    ->options(['elementary' => 'Elementary', 'jhs' => 'Jhs', 'shs' => 'Shs', 'integrated' => 'Integrated'])
                    ->required(),
                TextInput::make('contact_number'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('principal_name'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
