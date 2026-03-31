<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Select::make('role')
                    ->options([
                        'sdo_admin' => 'Sdo admin',
                        'principal' => 'Principal',
                        'health_coordinator' => 'Health coordinator',
                    ])
                    ->default('health_coordinator')
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Textarea::make('two_factor_secret')
                    ->columnSpanFull(),
                Textarea::make('two_factor_recovery_codes')
                    ->columnSpanFull(),
                DateTimePicker::make('two_factor_confirmed_at'),
                Select::make('school_id')
                    ->relationship('school', 'name'),

                Section::make('User Access')
                    ->description('Control panel access and grant time-limited permissions.')
                    ->schema([
                        Toggle::make('is_approved')
                            ->label('Approve User')
                            ->helperText('Enable this to allow the user to access the admin panel.')
                            ->default(false),

                        Repeater::make('temporary_permissions')
                            ->label('Temporary Permission Grants')
                            ->schema([
                                Select::make('permission')
                                    ->options(fn () => Permission::pluck('name', 'name')->toArray())
                                    ->required()
                                    ->searchable(),
                                DateTimePicker::make('expires_at')
                                    ->label('Expires At')
                                    ->required()
                                    ->native(false)
                                    ->minDate(now()->addMinute()),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add Temporary Grant')
                            ->afterStateHydrated(function (Repeater $component, ?User $record) {
                                if (! $record) {
                                    return;
                                }

                                $temporaryPermissions = $record->permissions()
                                    ->whereNotNull('expires_at')
                                    ->where('expires_at', '>', now())
                                    ->get()
                                    ->map(fn ($permission) => [
                                        'permission' => $permission->name,
                                        'expires_at' => $permission->pivot->expires_at,
                                    ])
                                    ->toArray();

                                $component->state($temporaryPermissions);
                            })
                            ->dehydrateStateUsing(function (array $state): array {
                                return $state;
                            }),
                    ])
                    ->collapsible(),

                Section::make('Permission Overrides')
                    ->description('Grant or revoke permissions beyond the user\'s role defaults.')
                    ->schema([
                        CheckboxList::make('permission_overrides')
                            ->options(fn () => Permission::pluck('name', 'name')->toArray())
                            ->descriptions(fn (User $record) => self::permissionDescriptions($record))
                            ->columns(2)
                            ->searchable()
                            ->afterStateHydrated(function (CheckboxList $component, ?User $record) {
                                if (! $record) {
                                    return;
                                }

                                $rolePermissionNames = $record->getPermissionsViaRoles()
                                    ->pluck('name')
                                    ->toArray();

                                $directPermissionNames = $record->permissions()
                                    ->where(function ($query) {
                                        $query->whereNull('expires_at')
                                            ->orWhere('expires_at', '>', now());
                                    })
                                    ->pluck('name')
                                    ->toArray();

                                $checked = array_unique(array_merge($rolePermissionNames, $directPermissionNames));
                                $component->state($checked);
                            })
                            ->dehydrateStateUsing(function (array $state): array {
                                return $state;
                            }),
                    ])
                    ->collapsible(),
            ]);
    }

    /**
     * Build permission descriptions showing which are inherited from role vs direct.
     */
    protected static function permissionDescriptions(User $record): array
    {
        $rolePermissionNames = $record->getPermissionsViaRoles()
            ->pluck('name')
            ->toArray();

        $directPermissions = $record->permissions()
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->get();

        $descriptions = [];
        foreach (Permission::pluck('name')->toArray() as $permissionName) {
            if (in_array($permissionName, $rolePermissionNames)) {
                $directGrant = $directPermissions->firstWhere('name', $permissionName);
                if ($directGrant && $directGrant->pivot->expires_at) {
                    $descriptions[$permissionName] = 'Role + expires '.Carbon::parse($directGrant->pivot->expires_at)->format('M j, Y');
                } else {
                    $descriptions[$permissionName] = 'Inherited from role';
                }
            } elseif ($directPermissions->contains('name', $permissionName)) {
                $directGrant = $directPermissions->firstWhere('name', $permissionName);
                if ($directGrant->pivot->expires_at) {
                    $descriptions[$permissionName] = 'Expires '.Carbon::parse($directGrant->pivot->expires_at)->format('M j, Y');
                } else {
                    $descriptions[$permissionName] = 'Directly granted';
                }
            } else {
                $descriptions[$permissionName] = 'Not granted';
            }
        }

        return $descriptions;
    }
}
