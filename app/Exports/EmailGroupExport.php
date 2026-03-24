<?php

namespace App\Exports;

use App\Models\EmailGroup;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class EmailGroupExport extends Exporter
{
    protected static ?string $model = EmailGroup::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('email')
                ->label('Email')
                ->enabledByDefault(true),

            ExportColumn::make('parent.title')
                ->label('Group Title')
                ->enabledByDefault(false),
        ];
    }

    public static function modifyQuery(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereNotNull('parent_id');
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $count = $export->successful_rows;

        return "Email group export completed. {$count} email(s) exported.";
    }
}
