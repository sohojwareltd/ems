<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Actions\Action;
use App\Filament\Resources\OrderResource\RelationManagers\DiscountsRelationManager;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Order Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Order #')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->color('primary'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Order Date')
                            ->dateTime('M j, Y g:i A'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'pending' => 'primary',
                                'paid' => 'success',
                                'failed' => 'danger',
                                'refunded' => 'warning',
                                'cancelled' => 'secondary',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('currency')
                            ->label('Currency'),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Customer Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Customer Name')
                            ->icon('heroicon-o-user'),
                        Infolists\Components\TextEntry::make('user.email')
                            ->label('Email')
                            ->icon('heroicon-o-envelope'),
                        Infolists\Components\TextEntry::make('user.phone')
                            ->label('Phone')
                            ->icon('heroicon-o-phone'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Shipping Address')
                    ->schema([
                        Infolists\Components\TextEntry::make('shipping_address.first_name')
                            ->label('First Name'),
                        Infolists\Components\TextEntry::make('shipping_address.last_name')
                            ->label('Last Name'),
                        Infolists\Components\TextEntry::make('shipping_address.email')
                            ->label('Email'),
                        Infolists\Components\TextEntry::make('shipping_address.phone')
                            ->label('Phone'),
                        Infolists\Components\TextEntry::make('shipping_address.company')
                            ->label('Company'),
                        Infolists\Components\TextEntry::make('shipping_address.address_line_1')
                            ->label('Address Line 1'),
                        Infolists\Components\TextEntry::make('shipping_address.address_line_2')
                            ->label('Address Line 2'),
                        Infolists\Components\TextEntry::make('shipping_address.city')
                            ->label('City'),
                        Infolists\Components\TextEntry::make('shipping_address.state')
                            ->label('State/Province'),
                        Infolists\Components\TextEntry::make('shipping_address.postal_code')
                            ->label('Postal Code'),
                        Infolists\Components\TextEntry::make('shipping_address.country')
                            ->label('Country'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Billing Address')
                    ->schema([
                        Infolists\Components\TextEntry::make('billing_address.first_name')
                            ->label('First Name'),
                        Infolists\Components\TextEntry::make('billing_address.last_name')
                            ->label('Last Name'),
                        Infolists\Components\TextEntry::make('billing_address.email')
                            ->label('Email'),
                        Infolists\Components\TextEntry::make('billing_address.phone')
                            ->label('Phone'),
                        Infolists\Components\TextEntry::make('billing_address.company')
                            ->label('Company'),
                        Infolists\Components\TextEntry::make('billing_address.address_line_1')
                            ->label('Address Line 1'),
                        Infolists\Components\TextEntry::make('billing_address.address_line_2')
                            ->label('Address Line 2'),
                        Infolists\Components\TextEntry::make('billing_address.city')
                            ->label('City'),
                        Infolists\Components\TextEntry::make('billing_address.state')
                            ->label('State/Province'),
                        Infolists\Components\TextEntry::make('billing_address.postal_code')
                            ->label('Postal Code'),
                        Infolists\Components\TextEntry::make('billing_address.country')
                            ->label('Country'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Payment Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('payment_status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'pending' => 'primary',
                                'paid' => 'success',
                                'failed' => 'danger',
                                'refunded' => 'warning',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('payment_method')
                            ->label('Payment Method'),
                        Infolists\Components\TextEntry::make('payment_intent_id')
                            ->label('Payment Intent ID')
                            ->copyable(),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Shipping Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('shipping_method')
                            ->label('Shipping Method'),
                        Infolists\Components\TextEntry::make('tracking')
                            ->label('Tracking Number')
                            ->copyable(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Order Items')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('lines')
                            ->schema([
                                Infolists\Components\TextEntry::make('product_name')
                                    ->label('Product')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('sku')
                                    ->label('SKU'),
                                Infolists\Components\TextEntry::make('price')
                                    ->label('Price')
                                    ->money('usd'),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Qty'),
                                Infolists\Components\TextEntry::make('total')
                                    ->label('Total')
                                    ->money('usd')
                                    ->weight('bold'),
                            ])
                            ->columns(5),
                    ]),

                Infolists\Components\Section::make('Order Summary')
                    ->schema([
                        Infolists\Components\TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('usd')
                            ->state(fn($record) => $record->subtotal),
                        Infolists\Components\TextEntry::make('total_discount')
                            ->label('Total Discount')
                            ->money('usd')
                            ->state(fn($record) => $record->total_discount)
                            ->color('danger'),
                        Infolists\Components\TextEntry::make('total')
                            ->label('Total')
                            ->money('usd')
                            ->weight('bold')
                            ->color('success'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Discounts')
                    ->description('Order discounts and their details.')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('discounts')
                            ->schema([
                                Infolists\Components\TextEntry::make('amount')
                                    ->label('Amount')
                                    ->money('usd')
                                    ->color('danger'),
                                Infolists\Components\TextEntry::make('type')
                                    ->label('Type')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'manual' => 'gray',
                                        'coupon' => 'primary',
                                        'loyalty' => 'success',
                                        'promotion' => 'warning',
                                        default => 'gray',
                                    }),
                                Infolists\Components\TextEntry::make('reason')
                                    ->label('Reason')
                                    ->markdown(),
                                Infolists\Components\TextEntry::make('applied_by')
                                    ->state(fn($record) => $record->appliedBy->name)
                                    ->label('Applied By')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Applied On')
                                    ->dateTime('M j, Y g:i A'),
                                Infolists\Components\Actions::make([
                                    Infolists\Components\Actions\Action::make('delete_discount')
                                        ->label('Delete')
                                        ->icon('heroicon-o-trash')
                                        ->color('danger')
                                        ->size('sm')
                                        ->action(function ($record, $livewire) {
                                            $discountId = $record->id;
                                            $order = $livewire->record;

                                            // Delete the discount
                                            $order->discounts()->where('id', $discountId)->delete();

                                            // Recalculate order total
                                            $order->recalculateTotal();

                                            \Filament\Notifications\Notification::make()
                                                ->success()
                                                ->title('Discount removed successfully')
                                                ->body('The discount has been removed from the order.')
                                                ->send();
                                        })
                                        ->requiresConfirmation()
                                        ->modalHeading('Delete Discount')
                                        ->modalDescription('Are you sure you want to delete this discount? This action cannot be undone.')
                                        ->modalSubmitActionLabel('Delete Discount'),
                                ]),
                            ])
                            ->columns(6),
                    ]),

                Infolists\Components\Section::make('Notes')
                    ->schema([
                        Infolists\Components\TextEntry::make('notes')
                            ->label('Order Notes')
                            ->markdown()
                            ->placeholder('No notes'),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Order History Timeline')
                    ->description('Complete history of order events and changes.')
                    ->schema([
                        Infolists\Components\ViewEntry::make('order_history_timeline')
                            ->view('filament.infolists.components.order-history-timeline')
                            ->state(fn($record) => $record->histories()->latest()->get()),
                    ]),
            ]);
    }

    
    protected function getHeaderActions(): array
    {
        return [
            Action::make('add_discount')
                ->label('Add Discount')
                ->icon('heroicon-o-plus-circle')
                ->form([
                    \Filament\Forms\Components\TextInput::make('amount')
                        ->label('Discount Amount')
                        ->numeric()
                        ->required()
                        ->prefix('$')
                        ->helperText('Enter the discount amount'),
                    \Filament\Forms\Components\Select::make('type')
                        ->label('Discount Type')
                        ->options([
                            'manual' => 'Manual Discount',
                            'coupon' => 'Coupon',
                            'loyalty' => 'Loyalty',
                            'promotion' => 'Promotion',
                        ])
                        ->default('manual')
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('reason')
                        ->label('Reason')
                        ->helperText('Optional reason for the discount')
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    $this->record->discounts()->create([
                        'amount' => $data['amount'],
                        'type' => $data['type'],
                        'reason' => $data['reason'],
                        'applied_by' => \Filament\Facades\Filament::auth()->id(),
                    ]);

                    // Recalculate order total
                    $this->record->recalculateTotal();

                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Discount added successfully')
                        ->body('The discount has been added to the order.')
                        ->send();
                })
                ->modalHeading('Add Discount to Order')
                ->modalDescription('Add a discount to this order. This will be tracked with your user information.')
                ->modalSubmitActionLabel('Add Discount')
                ->color('primary'),
            Action::make('recalculate_total')
                ->label('Recalculate Total')
                ->icon('heroicon-o-calculator')
                ->action(function (): void {
                    $this->record->recalculateTotal();
                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Order total recalculated')
                        ->body('The order total has been recalculated.')
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Recalculate Order Total')
                ->modalDescription('This will recalculate the order total based on current order lines and discounts.')
                ->modalSubmitActionLabel('Recalculate')
                ->color('warning'),
            Action::make('print_invoice')
                ->label('Print Invoice')
                ->icon('heroicon-o-printer')
                ->url(fn() => route('orders.print-invoice', $this->record))
                ->openUrlInNewTab()
                ->color('success'),
            Action::make('print_shipping_label')
                ->label('Print Shipping Label')
                ->icon('heroicon-o-printer')
                ->url(fn() => route('orders.print-shipping-label', $this->record))
                ->openUrlInNewTab()
                ->color('info'),
            DeleteAction::make('delete')
                ->label('Delete Order')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->action(function (): void {
                    $this->record->delete();
                }),
            EditAction::make('edit')
                ->label('Edit Order')
                ->icon('heroicon-o-pencil')
                ->color('primary')
                ->action(function (): void {
                    $this->record->update($this->record->toArray());
                }),
        ];
    }
    

    protected function getRelations(): array
    {
        return [
            DiscountsRelationManager::class,
        ];
    }


}
