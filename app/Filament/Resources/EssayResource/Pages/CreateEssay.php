<?php

namespace App\Filament\Resources\EssayResource\Pages;

use App\Filament\Resources\EssayResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEssay extends CreateRecord
{
    protected static string $resource = EssayResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Remove topics before saving
        $topics = $data['topics'] ?? [];
        unset($data['topics']);

        $record = parent::handleRecordCreation($data);

        // Sync pivot table after creating
        $record->topics()->sync($topics);

        return $record;
    }
}
