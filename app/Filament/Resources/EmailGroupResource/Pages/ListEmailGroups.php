<?php

namespace App\Filament\Resources\EmailGroupResource\Pages;

use App\Exports\EmailGroupExport;
use App\Filament\Resources\EmailGroupResource;
use App\Imports\EmailGroupImport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmailGroups extends ListRecords
{
    protected static string $resource = EmailGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            // Actions\ExportAction::make()
            //     ->exporter(EmailGroupExport::class)
            //     ->label('Export Emails'),
            // Actions\ImportAction::make()
            //     ->importer(EmailGroupImport::class)
            //     ->label('Import Emails'),
        ];
    }
}
