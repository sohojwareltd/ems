<?php

namespace App\Filament\Resources\EssayResource\Pages;

use App\Filament\Resources\EssayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEssay extends EditRecord
{
    protected static string $resource = EssayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $topics = $data['topics'] ?? [];
        unset($data['topics']);

        $record = parent::handleRecordUpdate($record, $data);

        // Sync pivot table after updating
        $record->topics()->sync($topics);

        return $record;
    }
}
