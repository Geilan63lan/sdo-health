<?php

namespace App\Filament\Resources\Schools\Tables;

use App\Enums\SchoolCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SchoolsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordAction(ViewAction::class)
            ->columns([
                TextColumn::make('school_id')
                    ->label('SCHOOL ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('NAME')
                    ->searchable(),
                TextColumn::make('address')
                    ->label('ADDRESS')
                    ->searchable(),
                TextColumn::make('category')
                    ->label('CATEGORY')
                    ->badge()
                    ->searchable(),
                TextColumn::make('contact_number')
                    ->label('CONTACT NUMBER')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('EMAIL')
                    ->searchable(),
                TextColumn::make('principal_name')
                    ->label('PRINCIPAL NAME')
                    ->searchable(),
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
                SelectFilter::make('category')
                    ->options([
                        SchoolCategory::ELEMENTARY->value => SchoolCategory::ELEMENTARY->label(),
                        SchoolCategory::JUNIOR_HIGH->value => SchoolCategory::JUNIOR_HIGH->label(),
                        SchoolCategory::SENIOR_HIGH->value => SchoolCategory::SENIOR_HIGH->label(),
                        SchoolCategory::OTHER->value => SchoolCategory::OTHER->label(),
                    ])
                    ->label('Category'),
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
