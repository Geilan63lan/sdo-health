<?php

namespace App\Filament\Resources\Students\Schemas;

use App\Enums\GradeLevel;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Academic Information')
                    ->schema([
                        Select::make('school_id')
                            ->relationship('school', 'name')
                            ->required()
                            ->hidden(fn () => auth()->user()->hasRole('health_coordinator'))
                            ->default(fn () => auth()->user()->hasRole('health_coordinator') ? auth()->user()->school_id : null)
                            ->columnSpanFull(),
                        Select::make('current_grade_level')
                            ->label('Grade Level')
                            ->options(GradeLevel::asSelectOptions())
                            ->required(),
                        TextInput::make('lrn')
                            ->label('LRN')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Personal Information')
                    ->schema([
                        TextInput::make('first_name')
                            ->label('First Name')
                            ->required(),
                        TextInput::make('middle_name')
                            ->label('Middle Name'),
                        TextInput::make('last_name')
                            ->label('Last Name')
                            ->required(),
                        TextInput::make('suffix')
                            ->label('Suffix'),
                        DatePicker::make('birth_date')
                            ->required(),
                        Grid::make(2)
                            ->schema([
                                Select::make('sex')
                                    ->label('Sex')
                                    ->options(['male' => 'Male', 'female' => 'Female'])
                                    ->required(),
                                Toggle::make('is_active')
                                    ->label('Is Active')
                                    ->extraAttributes(['class' => 'mt-9'])
                                    ->required()
                                    ->default(true),
                            ])
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Section::make('Guardian Information')
                    ->schema([
                        TextInput::make('guardian_name')
                            ->label('Guardian Name')
                            ->required(),
                        TextInput::make('guardian_contact')
                            ->label('Guardian Contact')
                            ->required(),
                        Select::make('guardian_relationship')
                            ->label('Relationship')
                            ->options([
                                'Father' => 'Father',
                                'Mother' => 'Mother',
                                'Grandparent' => 'Grandparent',
                                'Aunt' => 'Aunt',
                                'Uncle' => 'Uncle',
                            ])
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Address')
                    ->schema([
                        TextInput::make('address')
                            ->label('Address')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
