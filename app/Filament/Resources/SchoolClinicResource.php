<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolClinics\Pages\CreateSchoolClinic;
use App\Filament\Resources\SchoolClinics\Pages\EditSchoolClinic;
use App\Filament\Resources\SchoolClinics\Pages\ListSchoolClinics;
use App\Filament\Resources\SchoolClinics\Schemas\SchoolClinicForm;
use App\Filament\Resources\SchoolClinics\Tables\SchoolClinicsTable;
use App\Models\SchoolClinic;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SchoolClinicResource extends Resource
{
    protected static ?string $model = SchoolClinic::class;

    protected static UnitEnum|string|null $navigationGroup = 'School Management';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Schema $schema): Schema
    {
        return SchoolClinicForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SchoolClinicsTable::configure($table);
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
            'index' => ListSchoolClinics::route('/'),
            'create' => CreateSchoolClinic::route('/create'),
            'edit' => EditSchoolClinic::route('/{record}/edit'),
        ];
    }
}
