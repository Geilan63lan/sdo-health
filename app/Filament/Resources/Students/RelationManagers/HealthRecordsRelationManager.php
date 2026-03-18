<?php

namespace App\Filament\Resources\Students\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class HealthRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'healthRecords';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return 'Health Records';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordsTitleAttribute('record_date')
            ->columns([
                Tables\Columns\TextColumn::make('record_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('height_cm')
                    ->label('Height (cm)')
                    ->numeric(),
                Tables\Columns\TextColumn::make('weight_kg')
                    ->label('Weight (kg)')
                    ->numeric(),
                Tables\Columns\TextColumn::make('bmi')
                    ->label('BMI')
                    ->numeric(),
                Tables\Columns\TextColumn::make('bmi_category')
                    ->label('Category'),
                Tables\Columns\TextColumn::make('medical_conditions')
                    ->label('Medical Conditions')
                    ->limit(50),
                Tables\Columns\TextColumn::make('allergies')
                    ->label('Allergies')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
