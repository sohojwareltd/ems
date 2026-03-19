<?php

namespace App\Filament\Resources\AdminEmailResource\Pages;

use App\Filament\Resources\AdminEmailResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAdminEmail extends CreateRecord
{
    protected static string $resource = AdminEmailResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();

        return $data;
    }
}
