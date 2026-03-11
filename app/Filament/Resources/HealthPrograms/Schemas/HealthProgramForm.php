<?php

namespace App\Filament\Resources\HealthPrograms\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class HealthProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('school_id')
                    ->relationship('school', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Select::make('type')
                    ->options([
            'screening' => 'Screening',
            'vaccination' => 'Vaccination',
            'education' => 'Education',
            'counseling' => 'Counseling',
            'other' => 'Other',
        ])
                    ->required(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date'),
                Select::make('target_grade')
                    ->options([
            'Kinder' => 'Kinder',
            'Grade 1' => 'Grade1',
            'Grade 2' => 'Grade2',
            'Grade 3' => 'Grade3',
            'Grade 4' => 'Grade4',
            'Grade 5' => 'Grade5',
            'Grade 6' => 'Grade6',
            'Grade 7' => 'Grade7',
            'Grade 8' => 'Grade8',
            'Grade 9' => 'Grade9',
            'Grade 10' => 'Grade10',
            'Grade 11' => 'Grade11',
            'Grade 12' => 'Grade12',
            'All' => 'All',
        ]),
                Select::make('status')
                    ->options([
            'planned' => 'Planned',
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ])
                    ->default('planned')
                    ->required(),
                Select::make('coordinator_id')
                    ->relationship('coordinator', 'name')
                    ->required(),
            ]);
    }
}
