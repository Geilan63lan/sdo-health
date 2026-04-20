<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RolePermissions\Pages\EditRolePermissions;
use App\Filament\Resources\RolePermissions\Pages\ListRolePermissions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;
use BackedEnum;
use UnitEnum;

class RolePermissionResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationLabel = 'Permission Management';

    protected static UnitEnum|string|null $navigationGroup = 'User Management';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-lock-closed';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('sdo_admin');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role Information')
                    ->components([
                        TextInput::make('name')
                            ->label('Role Name')
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                    ]),
                Section::make('Permissions')
                    ->components([
                        CheckboxList::make('permissions')
                            ->relationship('permissions', 'name')
                            ->options(\Spatie\Permission\Models\Permission::query()
                                ->pluck('name', 'id'))
                            ->columns(2)
                            ->descriptions([
                                'view_admin_panel' => 'Access to admin panel',
                                'manage_schools' => 'Can create, view, edit, delete schools',
                                'manage_students' => 'Can create, view, edit, delete students',
                                'manage_health_records' => 'Can create, view, edit, delete health records',
                                'manage_vaccinations' => 'Can create, view, edit, delete vaccinations',
                                'manage_absences' => 'Can create, view, edit, delete absences',
                                'manage_health_programs' => 'Can create, view, edit, delete health programs',
                                'manage_permissions' => 'Can manage role permissions',
                                'view_students' => 'Can only view student records',
                                'view_schools' => 'Can only view school records',
                                'view_health_records' => 'Can only view health records',
                                'view_vaccinations' => 'Can only view vaccination records',
                                'view_absences' => 'Can only view absence records',
                                'view_health_programs' => 'Can only view health program records',
                            ])
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Role')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->counts('permissions')
                    ->alignment('center'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
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
            'index' => ListRolePermissions::route('/'),
            'edit' => EditRolePermissions::route('/{record}/edit'),
        ];
    }
}
