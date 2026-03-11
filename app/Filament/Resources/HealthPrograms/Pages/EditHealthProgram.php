<?php

namespace App\Filament\Resources\HealthPrograms\Pages;

use App\Filament\Resources\HealthProgramResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHealthProgram extends EditRecord
{
    protected static string $resource = HealthProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
