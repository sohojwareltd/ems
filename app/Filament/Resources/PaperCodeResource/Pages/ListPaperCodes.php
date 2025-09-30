<?php

namespace App\Filament\Resources\PaperCodeResource\Pages;

use App\Filament\Resources\PaperCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaperCodes extends ListRecords
{
    protected static string $resource = PaperCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
