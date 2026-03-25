<?php

namespace App\Filament\Resources\HealthExaminations\Pages;

use App\Filament\Resources\HealthExaminationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHealthExamination extends EditRecord
{
    protected static string $resource = HealthExaminationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
