<?php

namespace App\Filament\Resources\HealthExaminations\Pages;

use App\Filament\Resources\HealthExaminationResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHealthExamination extends EditRecord
{
    protected static string $resource = HealthExaminationResource::class;

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
