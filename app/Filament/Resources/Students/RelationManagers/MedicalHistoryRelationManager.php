<?php

namespace App\Filament\Resources\Students\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MedicalHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'medicalHistory';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Medical History';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Allergies')
                    ->description('Details about any known allergies.')
                    ->schema([
                        Toggle::make('has_allergies')
                            ->label('Has Allergies?')
                            ->reactive(),
                        CheckboxList::make('allergy_types')
                            ->label('Type of Allergy')
                            ->options([
                                'medicine' => 'Medicine',
                                'food' => 'Food',
                                'dust' => 'Dust',
                                'pollen' => 'Pollen',
                                'others' => 'Others',
                            ])
                            ->visible(fn (Get $get) => $get('has_allergies'))
                            ->columns(2),
                        TextInput::make('allergy_others')
                            ->label('Please specify other allergies')
                            ->visible(fn (Get $get) => $get('has_allergies') &&
                                collect($get('allergy_types'))->contains('others')
                            ),
                    ]),

                Section::make('Medical Conditions')
                    ->description('Ongoing medical conditions or health concerns.')
                    ->schema([
                        Toggle::make('has_medical_conditions')
                            ->label('Has Ongoing Medical Conditions?')
                            ->reactive(),
                        CheckboxList::make('condition_types')
                            ->label('Type of Condition')
                            ->options([
                                'asthma' => 'Asthma',
                                'seizure' => 'Seizure',
                                'diabetes' => 'Diabetes',
                                'heart disease' => 'Heart Disease',
                                'others' => 'Others',
                            ])
                            ->visible(fn (Get $get) => $get('has_medical_conditions'))
                            ->columns(2),
                        TextInput::make('condition_others')
                            ->label('Please specify other conditions')
                            ->visible(fn (Get $get) => $get('has_medical_conditions') &&
                                collect($get('condition_types'))->contains('others')
                            ),
                    ]),

                Section::make('Surgeries & Hospitalization')
                    ->schema([
                        Toggle::make('has_past_surgery')
                            ->label('Has past surgery or hospitalization?')
                            ->reactive(),
                        Textarea::make('surgery_details')
                            ->label('Surgery Details')
                            ->visible(fn (Get $get) => $get('has_past_surgery'))
                            ->columnSpanFull(),
                    ]),

                Section::make('Family History')
                    ->schema([
                        CheckboxList::make('family_history')
                            ->label('Family History of:')
                            ->options([
                                'hypertension' => 'Hypertension',
                                'diabetes' => 'Diabetes',
                                'cancer' => 'Cancer',
                                'asthma' => 'Asthma',
                                'others' => 'Others',
                            ])
                            ->reactive()
                            ->columns(2),
                        TextInput::make('cancer_type')
                            ->label('Type of Cancer')
                            ->visible(fn (Get $get) => collect($get('family_history'))->contains('cancer')
                            ),
                        TextInput::make('family_history_other')
                            ->label('Other Family History')
                            ->visible(fn (Get $get) => collect($get('family_history'))->contains('others')
                            ),
                    ]),

                Section::make('Lifestyle & Physical')
                    ->schema([
                        Toggle::make('smoke_exposure')
                            ->label('Exposure to Second-hand Smoke?'),
                        Select::make('dominant_hand')
                            ->label('Dominant Hand')
                            ->options([
                                'right' => 'Right',
                                'left' => 'Left',
                                'both' => 'Both/Ambidextrous',
                            ])
                            ->default('right')
                            ->required(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('has_allergies')
                    ->label('Allergies')
                    ->boolean(),
                Tables\Columns\IconColumn::make('has_medical_conditions')
                    ->label('Conditions')
                    ->boolean(),
                Tables\Columns\IconColumn::make('has_past_surgery')
                    ->label('Surgery')
                    ->boolean(),
                Tables\Columns\TextColumn::make('dominant_hand')
                    ->label('Handedness')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->hidden(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->medicalHistory()->exists()),
            ])
            ->bulkActions([
                //
            ]);
    }
}
