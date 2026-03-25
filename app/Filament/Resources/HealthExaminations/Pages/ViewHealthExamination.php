<?php

namespace App\Filament\Resources\HealthExaminations\Pages;

use App\Filament\Resources\HealthExaminationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHealthExamination extends ViewRecord
{
    protected static string $resource = HealthExaminationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
