<?php

namespace App\Filament\Resources\ReviewRoleResource\Pages;

use App\Filament\Resources\ReviewRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReviewRoles extends ListRecords
{
    protected static string $resource = ReviewRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
