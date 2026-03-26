<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Students\Pages\CreateStudent;
use App\Filament\Resources\Students\Pages\EditStudent;
use App\Filament\Resources\Students\Pages\ListStudents;
use App\Filament\Resources\Students\Pages\ViewStudent;
use App\Filament\Resources\Students\Schemas\StudentForm;
use App\Filament\Resources\Students\Tables\StudentsTable;
use App\Models\Student;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $policy = \App\Policies\StudentPolicy::class;

    protected static UnitEnum|string|null $navigationGroup = 'Student Management';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $label = 'Student';

    protected static ?string $pluralLabel = 'Students';

    public static function form(Schema $schema): Schema
    {
        return StudentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('health_coordinator') || auth()->user()->hasRole('principal')) {
            $query->where('school_id', auth()->user()->school_id);
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Students\RelationManagers\MedicalHistoryRelationManager::class,
            \App\Filament\Resources\Students\RelationManagers\HealthExaminationsRelationManager::class,
            \App\Filament\Resources\Students\RelationManagers\HealthProgressTimelineRelationManager::class,
            \App\Filament\Resources\Students\RelationManagers\VaccinationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'view' => ViewStudent::route('/{record}'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }
}
