<?php

namespace App\Filament\Resources\RolePermissions\Pages;

use App\Filament\Resources\RolePermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRolePermissions extends ListRecords
{
    protected static string $resource = RolePermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - roles should be created through database
        ];
    }
}
