<?php

namespace App\Filament\Resources\EmailLogResource\Pages;

use App\Filament\Resources\AdminEmailResource;
use App\Filament\Resources\EmailLogResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListEmailLogs extends ListRecords
{
    protected static string $resource = EmailLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('newCustomEmail')
                ->label('New Custom Email')
                ->icon('heroicon-o-envelope')
                ->color('primary')
                ->url(AdminEmailResource::getUrl('create')),
        ];
    }
}
