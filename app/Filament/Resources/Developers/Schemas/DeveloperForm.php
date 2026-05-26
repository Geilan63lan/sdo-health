<?php

namespace App\Filament\Resources\Developers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DeveloperForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('title'),
                FileUpload::make('photo')
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('developers')
                    ->imageEditor()
                    ->circleCropper()
                    ->maxSize(2048),
                Textarea::make('bio')
                    ->columnSpanFull(),
                TextInput::make('github_url')
                    ->url(),
                TextInput::make('linkedin_url')
                    ->url(),
                TextInput::make('portfolio_url')
                    ->url(),
                Textarea::make('quote')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
