<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HealthPrograms\Pages\CreateHealthProgram;
use App\Filament\Resources\HealthPrograms\Pages\EditHealthProgram;
use App\Filament\Resources\HealthPrograms\Pages\ListHealthPrograms;
use App\Filament\Resources\HealthPrograms\Schemas\HealthProgramForm;
use App\Filament\Resources\HealthPrograms\Tables\HealthProgramsTable;
use App\Models\HealthProgram;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class HealthProgramResource extends Resource
{
    protected static ?string $model = HealthProgram::class;

    protected static UnitEnum|string|null $navigationGroup = 'Health Services';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function form(Schema $schema): Schema
    {
        return HealthProgramForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HealthProgramsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHealthPrograms::route('/'),
            'create' => CreateHealthProgram::route('/create'),
            'edit' => EditHealthProgram::route('/{record}/edit'),
        ];
    }
}
