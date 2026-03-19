<?php

namespace App\Filament\Resources\Students\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class VaccinationsRelationManager extends RelationManager
{
    protected static string $relationship = 'vaccinations';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return 'Vaccination Records';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vaccine_name')
                    ->label('Vaccine')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_given')
                    ->label('Date Given')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dose_number')
                    ->label('Dose')
                    ->numeric(),
                Tables\Columns\TextColumn::make('administered_by')
                    ->label('Administered By'),
                Tables\Columns\TextColumn::make('batch_number')
                    ->label('Batch Number'),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
