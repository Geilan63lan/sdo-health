<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lrn')
                    ->label('LRN')
                    ->searchable(),
                TextColumn::make('name')
                    ->formatStateUsing(function ($state, $record) {
                        $name = $record->first_name;
                        if ($record->middle_name) {
                            $name .= ' ' . $record->middle_name;
                        }
                        if ($record->last_name) {
                            $name .= ' ' . $record->last_name;
                        }
                        if ($record->suffix) {
                            $name .= ' ' . $record->suffix;
                        }
                        return $name;
                    })
                    ->searchable(query: function ($query, $search) {
                        return $query->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    }),
                TextColumn::make('school.name')
                    ->label('School')
                    ->searchable(),
                TextColumn::make('birth_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('sex')
                    ->badge(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
