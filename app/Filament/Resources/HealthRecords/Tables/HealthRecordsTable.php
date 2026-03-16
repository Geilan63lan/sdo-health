<?php

namespace App\Filament\Resources\HealthRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HealthRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordAction(ViewAction::class)
            ->columns([
                TextColumn::make('student.first_name')
                    ->label('STUDENT')
                    ->formatStateUsing(fn ($record) => "{$record->student->first_name} {$record->student->last_name}")
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                TextColumn::make('record_date')
                    ->label('RECORD DATE')
                    ->date()
                    ->sortable(),
                TextColumn::make('height_cm')
                    ->label('HEIGHT (CM)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('weight_kg')
                    ->label('WEIGHT (KG)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bmi')
                    ->label('BMI')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bmi_category')
                    ->label('CATEGORY')
                    ->searchable(),
                TextColumn::make('recordedBy.name')
                    ->label('RECORDED BY')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('CREATED AT')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('UPDATED AT')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->extraModalFooterActions([
                        EditAction::make()
                            ->cancelParentActions(),
                    ]),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
