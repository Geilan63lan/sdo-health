<?php

namespace App\Filament\Resources\SchoolClinics\Pages;

use App\Filament\Resources\SchoolClinicResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSchoolClinic extends EditRecord
{
    protected static string $resource = SchoolClinicResource::class;

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
}
