<?php

namespace App\Filament\Resources\PaperCodeResource\Pages;

use App\Filament\Resources\PaperCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaperCode extends EditRecord
{
    protected static string $resource = PaperCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
