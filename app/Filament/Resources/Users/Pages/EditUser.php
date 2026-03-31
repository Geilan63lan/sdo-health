<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Permission;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cancel')
                ->label('Cancel')
                ->url(fn () => static::getResource()::getUrl('index'))
                ->color('gray'),
            $this->getSaveFormAction()
                ->formId('form'),
            DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function beforeSave(): void
    {
        /** @var User $user */
        $user = $this->record;
        $data = $this->data;

        // Sync Spatie role with the role enum column
        $newRole = $data['role'];
        if ($user->role !== $newRole) {
            $user->syncRoles([$newRole]);
        }

        // Get role-based permissions for the (possibly new) role
        $rolePermissionNames = $user->roles()
            ->where('name', $newRole)
            ->first()
            ?->permissions
            ?->pluck('name')
            ->toArray() ?? [];

        // Get checked permissions from the form
        $checkedPermissions = $data['permission_overrides'] ?? [];

        // Determine which permissions are overrides (not in role defaults)
        $overridePermissions = array_diff($checkedPermissions, $rolePermissionNames);

        // Get temporary permission grants from the form
        $temporaryPermissions = $data['temporary_permissions'] ?? [];

        // Build the list of permissions to store as direct overrides
        $directPermissionsToSync = [];

        // Add permanent overrides (permissions not in role defaults, without expiry)
        foreach ($overridePermissions as $permName) {
            // Skip if this permission is handled by the temporary grants repeater
            $hasTemporary = collect($temporaryPermissions)->contains('permission', $permName);
            if (! $hasTemporary) {
                $directPermissionsToSync[$permName] = ['expires_at' => null];
            }
        }

        // Add temporary permissions with expiry
        foreach ($temporaryPermissions as $temp) {
            $permName = $temp['permission'];
            $expiresAt = $temp['expires_at'];
            $directPermissionsToSync[$permName] = ['expires_at' => $expiresAt];
        }

        // Sync direct permissions
        $user->permissions()->detach();

        foreach ($directPermissionsToSync as $permName => $pivotData) {
            $permission = Permission::where('name', $permName)->first();
            if ($permission) {
                $user->permissions()->attach($permission->id, $pivotData);
            }
        }

        $user->unsetRelation('permissions');
    }
}
