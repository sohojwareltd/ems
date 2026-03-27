<?php

namespace App\Filament\Resources\AdminEmailResource\Pages;

use App\Filament\Resources\AdminEmailResource;
use App\Filament\Resources\EmailLogResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAdminEmail extends CreateRecord
{
    protected static string $resource = AdminEmailResource::class;

    protected static bool $canCreateAnother = false;

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Send');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();

        return $data;
    }

    protected function afterCreate(): void
    {
        AdminEmailResource::sendEmail($this->record);
    }

    protected function getRedirectUrl(): string
    {
        return EmailLogResource::getUrl('index');
    }
}
