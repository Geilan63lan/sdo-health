<?php

namespace App\Filament\Resources\HealthExaminations\Tables;

use App\Helpers\HealthLegend;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class HealthExaminationsTable
{
    public static function configure(Table $table): Table
    {
        $map = HealthLegend::getMap();

        return $table
            ->columns([
                TextColumn::make('student.full_name')
                    ->label('Student')
                    ->searchable(['first_name', 'last_name', 'lrn'])
                    ->sortable(),
                TextColumn::make('grade_level')
                    ->label('Grade Level')
                    ->sortable(),
                TextColumn::make('date_of_examination')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('height_cm')
                    ->label('Height (cm)')
                    ->numeric(),
                TextColumn::make('weight_kg')
                    ->label('Weight (kg)')
                    ->numeric(),
                TextColumn::make('ns_bmi_for_age')
                    ->label('BMI-for-Age')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => HealthLegend::label('ns_bmi', $state))
                    ->color(fn (string $state): string => match ($state) {
                        'a' => 'danger',
                        'c' => 'warning',
                        'd' => 'success',
                        'e' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('ns_height_for_age')
                    ->label('Height-for-Age')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => HealthLegend::label('ns_height', $state))
                    ->color(fn (string $state): string => match ($state) {
                        'f' => 'danger',
                        'g' => 'warning',
                        'h' => 'success',
                        'i' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('examinedBy.name')
                    ->label('Examined By')
                    ->sortable(),
            ])
            ->defaultSort('date_of_examination', 'desc')
            ->filters([
                SelectFilter::make('grade_level')
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
                SelectFilter::make('ns_bmi_for_age')
                    ->label('BMI-for-Age')
                    ->options($map['ns_bmi']),
                SelectFilter::make('ns_height_for_age')
                    ->label('Height-for-Age')
                    ->options($map['ns_height']),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
