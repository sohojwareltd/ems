<?php

namespace App\Filament\Resources\ExamboardResource\Pages;

use App\Filament\Resources\ExamboardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExamboard extends EditRecord
{
    protected static string $resource = ExamboardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
