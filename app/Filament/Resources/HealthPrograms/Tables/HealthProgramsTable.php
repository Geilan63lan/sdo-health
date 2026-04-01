<?php

namespace App\Filament\Resources\HealthPrograms\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HealthProgramsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('school.name')
                    ->label('SCHOOL')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('NAME')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('TYPE')
                    ->badge(),
                TextColumn::make('start_date')
                    ->label('START DATE')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('END DATE')
                    ->date()
                    ->sortable(),
                TextColumn::make('target_grade')
                    ->label('TARGET GRADE')
                    ->badge(),
                TextColumn::make('status')
                    ->label('STATUS')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'ongoing' => 'info',
                        'cancelled' => 'danger',
                        'planned' => 'warning',
                        default => 'secondary',
                    }),
                TextColumn::make('remarks')
                    ->label('REMARKS')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('coordinator.name')
                    ->label('COORDINATOR')
                    ->searchable(),
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
                EditAction::make()
                    ->visible(fn () => auth()->user()->hasRole('sdo_admin') || auth()->user()->hasPermissionTo('manage_health_programs')),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole('sdo_admin') || auth()->user()->hasPermissionTo('manage_health_programs')),
                ]),
            ]);
    }
}
