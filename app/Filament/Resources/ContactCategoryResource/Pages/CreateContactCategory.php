<?php

namespace App\Filament\Resources\ContactCategoryResource\Pages;

use App\Filament\Resources\ContactCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContactCategory extends CreateRecord
{
    protected static string $resource = ContactCategoryResource::class;
}
