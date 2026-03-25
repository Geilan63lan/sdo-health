<?php

namespace App\Filament\Resources\HealthExaminations\Pages;

use App\Filament\Resources\HealthExaminationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHealthExaminations extends ListRecords
{
    protected static string $resource = HealthExaminationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
