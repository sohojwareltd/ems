<?php

namespace App\Imports;

use App\Models\EmailGroup;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class EmailGroupImport extends Importer
{
    protected static ?string $model = EmailGroup::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('email')
                ->label('Email')
                ->requiredMapping()
                ->rules(['required', 'email']),

            ImportColumn::make('parent_id')
                ->label('Group Title')
                ->fillRecordUsing(function (EmailGroup $record, string $state): void {
                    // Accept group title and resolve to parent_id
                    $parent = EmailGroup::whereNull('parent_id')
                        ->where('title', $state)
                        ->first();

                    $record->parent_id = $parent?->id;
                }),
        ];
    }

    public function resolveRecord(): ?EmailGroup
    {
        return new EmailGroup();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $count = $import->successful_rows;

        return "Email group import completed. {$count} email(s) imported successfully.";
    }
}
