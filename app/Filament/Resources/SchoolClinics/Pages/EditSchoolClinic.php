<?php

namespace App\Filament\Resources\SchoolClinics\Pages;

use App\Filament\Resources\SchoolClinicResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSchoolClinic extends EditRecord
{
    protected static string $resource = SchoolClinicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
