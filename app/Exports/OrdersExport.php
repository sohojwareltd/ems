<?php

namespace App\Exports;

use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class OrdersExport extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('Order ID')
                ->description('Unique order identifier'),

            ExportColumn::make('user.name')
                ->label('Customer Name')
                ->description('Customer full name'),

            ExportColumn::make('user.email')
                ->label('Customer Email')
                ->description('Customer email address'),

            ExportColumn::make('status')
                ->label('Order Status')
                ->description('Current order status'),

            ExportColumn::make('payment_status')
                ->label('Payment Status')
                ->description('Payment processing status'),

            ExportColumn::make('payment_method')
                ->label('Payment Method')
                ->description('Method used for payment'),

            ExportColumn::make('total')
                ->label('Order Total')
                ->description('Total order amount')
                ->formatStateUsing(fn ($state) => '$' . number_format($state, 2)),

            ExportColumn::make('currency')
                ->label('Currency')
                ->description('Order currency'),

            ExportColumn::make('shipping_method')
                ->label('Shipping Method')
                ->description('Shipping method used'),

            ExportColumn::make('tracking')
                ->label('Tracking Number')
                ->description('Shipping tracking number'),

            ExportColumn::make('shipping_address')
                ->label('Shipping Address')
                ->description('Complete shipping address')
                ->formatStateUsing(function ($state) {
                    if (!is_array($state)) return 'N/A';
                    return collect($state)->filter()->implode(', ');
                }),

            ExportColumn::make('billing_address')
                ->label('Billing Address')
                ->description('Complete billing address')
                ->formatStateUsing(function ($state) {
                    if (!is_array($state)) return 'N/A';
                    return collect($state)->filter()->implode(', ');
                }),

            ExportColumn::make('notes')
                ->label('Order Notes')
                ->description('Additional order notes'),

            ExportColumn::make('lines_count')
                ->label('Items Count')
                ->description('Number of items in order')
                ->counts('lines'),

            ExportColumn::make('created_at')
                ->label('Order Date')
                ->description('When the order was created')
                ->formatStateUsing(fn ($state) => $state->format('Y-m-d H:i:s')),

            ExportColumn::make('updated_at')
                ->label('Last Updated')
                ->description('When the order was last updated')
                ->formatStateUsing(fn ($state) => $state->format('Y-m-d H:i:s')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'Your orders export has completed and is ready to download.';
    }
} 