<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament.resources.order-resource.pages.list-orders';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\OrderStatsOverview::class,
        ];
    }

} 