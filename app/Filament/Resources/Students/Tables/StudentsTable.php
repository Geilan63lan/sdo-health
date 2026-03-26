<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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
                    ->searchable(),
                TextColumn::make('first_name')
                    ->label('NAME')
                    ->formatStateUsing(fn ($record) => $record->full_name)
                    ->searchable(['first_name', 'middle_name', 'last_name'])
                    ->sortable(),
                TextColumn::make('school.name')
                    ->label('SCHOOL')
                    ->searchable(),
                TextColumn::make('birth_date')
                    ->label('BIRTH DATE')
                    ->date()
                    ->sortable(),
                TextColumn::make('current_grade_level')
                    ->label('GRADE LEVEL')
                    ->sortable(),
                TextColumn::make('sex')
                    ->label('SEX')
                    ->badge(),
                IconColumn::make('is_active')
                    ->label('IS ACTIVE')
                    ->boolean(),
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
                SelectFilter::make('current_grade_level')
                    ->label('Grade Level')
                    ->options([
                        'Kinder' => 'Kinder',
                        'Grade 1' => 'Grade 1',
                        'Grade 2' => 'Grade 2',
                        'Grade 3' => 'Grade 3',
                        'Grade 4' => 'Grade 4',
                        'Grade 5' => 'Grade 5',
                        'Grade 6' => 'Grade 6',
                        'Grade 7' => 'Grade 7',
                        'Grade 8' => 'Grade 8',
                        'Grade 9' => 'Grade 9',
                        'Grade 10' => 'Grade 10',
                        'Grade 11' => 'Grade 11',
                        'Grade 12' => 'Grade 12',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
