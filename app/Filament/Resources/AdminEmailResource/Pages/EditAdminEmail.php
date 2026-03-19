<?php

namespace App\Filament\Resources\AdminEmailResource\Pages;

use App\Filament\Resources\AdminEmailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminEmail extends EditRecord
{
    protected static string $resource = AdminEmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('send')
                ->label('Send Email')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn () => AdminEmailResource::sendEmail($this->record)),
            Actions\DeleteAction::make(),
        ];
    }
}
