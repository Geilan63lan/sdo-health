<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordAction(ViewAction::class)
            ->columns([
                TextColumn::make('lrn')
                    ->label('LRN')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('first_name')
                    ->label('FIRST NAME')
                    ->formatStateUsing(fn ($record) => "{$record->first_name} {$record->middle_name} {$record->last_name} {$record->suffix}")
                    ->searchable(['first_name', 'middle_name', 'last_name'])
                    ->sortable(),
                TextColumn::make('school.name')
                    ->label('SCHOOL')
                    ->searchable(),
                TextColumn::make('birth_date')
                    ->label('BIRTH DATE')
                    ->date()
                    ->sortable(),
                TextColumn::make('sex')
                    ->label('SEX')
                    ->badge(),
                IconColumn::make('is_active')
                    ->label('IS ACTIVE')
                    ->boolean(),
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
