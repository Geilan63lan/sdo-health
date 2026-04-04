<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getView(): string
    {
        return 'filament.resources.student.view-student';
    }
}
