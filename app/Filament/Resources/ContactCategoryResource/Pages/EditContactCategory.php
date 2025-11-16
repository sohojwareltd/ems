<?php

namespace App\Filament\Resources\ContactCategoryResource\Pages;

use App\Filament\Resources\ContactCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContactCategory extends EditRecord
{
    protected static string $resource = ContactCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
