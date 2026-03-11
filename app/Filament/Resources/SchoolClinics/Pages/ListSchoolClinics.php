<?php

namespace App\Filament\Resources\SchoolClinics\Pages;

use App\Filament\Resources\SchoolClinicResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSchoolClinics extends ListRecords
{
    protected static string $resource = SchoolClinicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
