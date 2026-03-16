<?php

namespace App\Filament\Resources\Vaccinations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VaccinationsTable
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
                TextColumn::make('vaccine_name')
                    ->label('VACCINE NAME')
                    ->searchable(),
                TextColumn::make('date_given')
                    ->label('DATE GIVEN')
                    ->date()
                    ->sortable(),
                TextColumn::make('dose_number')
                    ->label('DOSE NUMBER')
                    ->searchable(),
                TextColumn::make('administered_by')
                    ->label('ADMINISTERED BY')
                    ->searchable(),
                TextColumn::make('batch_number')
                    ->label('BATCH NUMBER')
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
