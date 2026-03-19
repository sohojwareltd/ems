<?php

namespace App\Filament\Resources\AdminEmailResource\Pages;

use App\Filament\Resources\AdminEmailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminEmails extends ListRecords
{
    protected static string $resource = AdminEmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
