<?php

namespace App\Filament\Resources\RolePermissions\Pages;

use App\Filament\Resources\RolePermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRolePermissions extends EditRecord
{
    protected static string $resource = RolePermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
