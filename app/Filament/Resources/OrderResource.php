<?php

namespace App\Filament\Resources;

use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\OrderLinesRelationManager;
use App\Filament\Actions\TestOrderEmailAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Filament\Resources\ResourcePermissionTrait;


class OrderResource extends Resource
{
    use ResourcePermissionTrait;
    protected static ?string $model = Order::class;
    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) Order::query()->count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Customer & Status')
                ->description('Select the customer and set the order status and payment details.')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->nullable()
                        ->helperText('Select the customer for this order.'),
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed',
                            'refunded' => 'Refunded',
                            'cancelled' => 'Cancelled',
                        ])->required()->helperText('Order status.'),
                    Forms\Components\Select::make('payment_method')
                        ->options([
                            'stripe' => 'Stripe',
                            'paypal' => 'PayPal',
                            'cod' => 'Cash on Delivery',
                        ])->nullable()->helperText('Payment method used.'),
                    Forms\Components\Select::make('payment_status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed',
                            'refunded' => 'Refunded',
                        ])->required()->helperText('Payment status.'),
                    Forms\Components\TextInput::make('payment_intent_id')->maxLength(255)->nullable()->helperText('Payment intent/reference ID.'),
                ])->columns(2),
            Forms\Components\Section::make('Order Details')
                ->description('Set the order total, currency, shipping, and tracking information.')
                ->schema([
                    Forms\Components\TextInput::make('total')->numeric()->required()->helperText('Order total amount.'),
                    Forms\Components\TextInput::make('currency')->maxLength(10)->default('USD')->helperText('Currency code.'),
                    Forms\Components\TextInput::make('shipping_method')->maxLength(255)->nullable()->helperText('Shipping method used.'),
                    Forms\Components\TextInput::make('tracking')->maxLength(255)->nullable()->helperText('Tracking number or URL.'),
                ])->columns(2),
            Forms\Components\Section::make('Addresses & Notes')
                ->description('Enter shipping and billing addresses, and any special notes for the order.')
                ->schema([
                    // Forms\Components\KeyValue::make('shipping_address')->label('Shipping Address')->helperText('Shipping address details.'),
                    Forms\Components\KeyValue::make('billing_address')->label('Billing Address')->helperText('Billing address details.'),
                    Forms\Components\Textarea::make('notes')->nullable()->helperText('Order notes or special instructions.')->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            // Order Identification
            Tables\Columns\TextColumn::make('id')
                ->label('Order #')
                ->sortable()
                ->searchable()
                ->weight('bold')
                ->color('primary'),
            
            // Customer Information
            Tables\Columns\TextColumn::make('user.name')
                ->label('Customer')
                ->searchable()
                ->sortable()
                ->description(fn ($record) => $record->user->email ?? 'Guest')
                ->icon('heroicon-o-user'),
            
            // Order Summary
            Tables\Columns\TextColumn::make('lines_count')
                ->label('Items')
                ->counts('lines')
                ->sortable()
                ->icon('heroicon-o-shopping-bag'),
            
            Tables\Columns\TextColumn::make('total')
                ->label('Total')
                ->money('usd')
                ->sortable()
                ->weight('bold')
                ->color('success'),
            
            // Order Status
            Tables\Columns\BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'gray' => 'pending',
                    'blue' => 'confirmed',
                    'yellow' => 'processing',
                    'purple' => 'shipped',
                    'green' => 'delivered',
                    'orange' => 'returned',
                    'red' => 'refunded',
                    'secondary' => 'cancelled',
                    'success' => 'completed',
                ])
                ->sortable()
                ->icon(fn (string $state): string => match ($state) {
                    'pending' => 'heroicon-o-clock',
                    'confirmed' => 'heroicon-o-check-circle',
                    'processing' => 'heroicon-o-cog-6-tooth',
                    'shipped' => 'heroicon-o-truck',
                    'delivered' => 'heroicon-o-home',
                    'returned' => 'heroicon-o-arrow-uturn-left',
                    'refunded' => 'heroicon-o-arrow-path',
                    'cancelled' => 'heroicon-o-x-mark',
                    'completed' => 'heroicon-o-check-badge',
                    default => 'heroicon-o-question-mark-circle',
                }),
            
            // Payment Information
            Tables\Columns\BadgeColumn::make('payment_status')
                ->label('Payment')
                    ->colors([
                    'primary' => 'pending',
                    'success' => 'paid',
                    'danger' => 'failed',
                    'warning' => 'refunded',
                ])
                ->sortable(),
            
            Tables\Columns\TextColumn::make('payment_method')
                ->label('Payment Method')
                ->badge()
                ->color('gray'),
            
            // Shipping Information
            // Tables\Columns\TextColumn::make('shipping_method')
            //     ->label('Shipping')
            //     ->badge()
            //     ->color('blue')
            //     ->icon('heroicon-o-truck'),
            
            // Tables\Columns\TextColumn::make('tracking')
            //     ->label('Tracking')
            //     ->copyable()
            //     ->icon('heroicon-o-qr-code')
            //     ->placeholder('No tracking'),
            
            // Location Information
            // Tables\Columns\TextColumn::make('shipping_address.city')
            //     ->label('City')
            //     ->getStateUsing(fn ($record) => $record->shipping_address['city'] ?? 'N/A')
            //     ->searchable()
            //     ->sortable()
            //     ->icon('heroicon-o-map-pin'),
            
            // Tables\Columns\TextColumn::make('shipping_address.country')
            //     ->label('Country')
            //     ->getStateUsing(fn ($record) => $record->shipping_address['country'] ?? 'N/A')
            //     ->searchable()
            //     ->sortable(),
            
            // Timestamps
            Tables\Columns\TextColumn::make('created_at')
                ->label('Order Date')
                ->dateTime('M j, Y g:i A')
                ->sortable()
                ->icon('heroicon-o-calendar'),
            
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Last Updated')
                ->dateTime('M j, Y g:i A')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            // Quick Status Filters
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'returned' => 'Returned',
                    'refunded' => 'Refunded',
                    'cancelled' => 'Cancelled',
                    'completed' => 'Completed',
                ])
                ->multiple()
                ->label('Order Status')
                ->indicateUsing(function (array $data): ?string {
                    if (! $data['values']) {
                        return null;
                    }
                    return 'Status: ' . collect($data['values'])->implode(', ');
                }),



            // Payment Status Filters
            Tables\Filters\SelectFilter::make('payment_status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'failed' => 'Failed',
                    'refunded' => 'Refunded',
                ])
                ->multiple()
                ->label('Payment Status'),

            // Payment Method Filters
            Tables\Filters\SelectFilter::make('payment_method')
                ->options([
                    'stripe' => 'Stripe',
                    'paypal' => 'PayPal',
                    'cod' => 'Cash on Delivery',
                ])
                ->multiple()
                ->label('Payment Method'),

            // Shipping Method Filters
            // Tables\Filters\SelectFilter::make('shipping_method')
            //     ->options([
            //         'FedEx' => 'FedEx',
            //         'UPS' => 'UPS',
            //         'USPS' => 'USPS',
            //         'DHL' => 'DHL',
            //         'Standard Shipping' => 'Standard Shipping',
            //         'Express Shipping' => 'Express Shipping',
            //     ])
            //     ->multiple()
            //     ->label('Shipping Method'),

            // Date Filters
            Tables\Filters\Filter::make('created_at')
                ->form([
                    Forms\Components\DatePicker::make('created_from')
                        ->label('From Date'),
                    Forms\Components\DatePicker::make('created_until')
                        ->label('To Date'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
                ->label('Order Date Range')
                ->indicateUsing(function (array $data): ?string {
                    if (! $data['created_from'] && ! $data['created_until']) {
                        return null;
                    }
                    $indicator = 'Date: ';
                    if ($data['created_from']) {
                        $indicator .= 'from ' . $data['created_from'];
                    }
                    if ($data['created_until']) {
                        $indicator .= ' to ' . $data['created_until'];
                    }
                    return $indicator;
                }),

            // Quick Date Filters
            Tables\Filters\Filter::make('today')
                ->label('Today')
                ->query(fn (Builder $query): Builder => $query->whereDate('created_at', today()))
                ->toggle(),

            Tables\Filters\Filter::make('yesterday')
                ->label('Yesterday')
                ->query(fn (Builder $query): Builder => $query->whereDate('created_at', today()->subDay()))
                ->toggle(),

            Tables\Filters\Filter::make('this_week')
                ->label('This Week')
                ->query(fn (Builder $query): Builder => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]))
                ->toggle(),

            Tables\Filters\Filter::make('this_month')
                ->label('This Month')
                ->query(fn (Builder $query): Builder => $query->whereMonth('created_at', now()->month))
                ->toggle(),

            Tables\Filters\Filter::make('last_month')
                ->label('Last Month')
                ->query(fn (Builder $query): Builder => $query->whereMonth('created_at', now()->subMonth()->month))
                ->toggle(),

            // Customer Filter
            Tables\Filters\SelectFilter::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->label('Customer'),

            // Country Filter
            Tables\Filters\SelectFilter::make('country')
                ->label('Country')
                ->options([
                    'US' => 'United States',
                    'CA' => 'Canada',
                    'GB' => 'United Kingdom',
                    'FR' => 'France',
                    'DE' => 'Germany',
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when($data['value'], function (Builder $query, $country) {
                        return $query->whereJsonContains('shipping_address->country', $country);
                    });
                }),

            // Amount Range Filter
            Tables\Filters\Filter::make('amount_range')
                ->form([
                    Forms\Components\TextInput::make('min_amount')
                        ->label('Minimum Amount')
                        ->numeric()
                        ->placeholder('0.00'),
                    Forms\Components\TextInput::make('max_amount')
                        ->label('Maximum Amount')
                        ->numeric()
                        ->placeholder('1000.00'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['min_amount'],
                            fn (Builder $query, $amount): Builder => $query->where('total', '>=', $amount),
                        )
                        ->when(
                            $data['max_amount'],
                            fn (Builder $query, $amount): Builder => $query->where('total', '<=', $amount),
                        );
                })
                ->label('Amount Range')
                ->indicateUsing(function (array $data): ?string {
                    if (! $data['min_amount'] && ! $data['max_amount']) {
                        return null;
                    }
                    $indicator = 'Amount: ';
                    if ($data['min_amount']) {
                        $indicator .= '$' . $data['min_amount'] . '+';
                    }
                    if ($data['max_amount']) {
                        $indicator = 'Amount: up to $' . $data['max_amount'];
                    }
                    if ($data['min_amount'] && $data['max_amount']) {
                        $indicator = 'Amount: $' . $data['min_amount'] . ' - $' . $data['max_amount'];
                    }
                    return $indicator;
                }),
        ])
        ->actions([
            Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make()
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->modalContent(fn ($record) => view('filament.resources.order-resource.pages.view-order-modal', ['record' => $record]))
                    ->modalWidth('7xl'),
                TestOrderEmailAction::make(),
                Tables\Actions\Action::make('add_shipping_method')
                    ->label('Add Shipping Method')
                    ->icon('heroicon-o-truck')
                    ->visible(fn ($record) => $record->status !== 'pending')
                    ->form([
                        // \Filament\Forms\Components\Select::make('shipping_method')
                        //     ->label('Shipping Method')
                        //     ->options(\App\Models\ShippingMethod::all()->pluck('name', 'name'))
                        //     ->searchable()
                        //     ->required()
                        //     ->helperText('Select a shipping method for this order'),
                        \Filament\Forms\Components\TextInput::make('tracking')
                            ->label('Tracking Number')
                            ->maxLength(255)
                            ->helperText('Optional tracking number or URL'),
                        \Filament\Forms\Components\Textarea::make('notes')
                            ->label('Shipping Notes')
                            ->rows(3)
                            ->helperText('Optional notes about shipping'),
                    ])
                    ->fillForm(function ($record): array {
                        return [
                            'shipping_method' => $record->shipping_method,
                            'tracking' => $record->tracking,
                        ];
                    })
                    ->action(function (array $data, $record): void {
                        $record->update([
                            'shipping_method' => $data['shipping_method'],
                            'tracking' => $data['tracking'] ?? null,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Shipping method updated successfully')
                            ->body('The shipping method has been updated for this order.')
                            ->send();
                    })
                    ->modalHeading('Update Shipping Method')
                    ->modalDescription('Update shipping method and tracking information for this order.')
                    ->modalSubmitActionLabel('Update Shipping Method')
                    ->color('info'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print_invoice')
                    ->label('Print Invoice')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('orders.print-invoice', $record), true),
                Tables\Actions\Action::make('print_shipping_label')
                    ->label('Print Shipping Label')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('orders.print-shipping-label', $record), true),
            ])->icon('heroicon-o-ellipsis-vertical'),
            
            // Status Change Actions
            Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('confirm_order')
                    ->label('Confirm')
                    ->icon('heroicon-o-check-circle')
                    ->color('blue')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'confirmed']);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Order')
                    ->modalDescription('This will mark the order as confirmed and ready for processing.')
                    ->modalSubmitActionLabel('Confirm Order'),
                
                Tables\Actions\Action::make('mark_processing')
                    ->label('Processing')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->color('yellow')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'confirmed']))
                    ->action(function ($record) {
                        $record->update(['status' => 'processing']);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark as Processing')
                    ->modalDescription('This will mark the order as being processed.')
                    ->modalSubmitActionLabel('Mark as Processing'),
                
                Tables\Actions\Action::make('mark_shipped')
                    ->label('Shipped')
                    ->icon('heroicon-o-truck')
                    ->color('purple')
                    ->visible(fn ($record) => in_array($record->status, ['confirmed', 'processing']))
                    ->action(function ($record) {
                        $record->update(['status' => 'shipped']);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark as Shipped')
                    ->modalDescription('This will mark the order as shipped.')
                    ->modalSubmitActionLabel('Mark as Shipped'),
                
                Tables\Actions\Action::make('mark_delivered')
                    ->label('Delivered')
                    ->icon('heroicon-o-home')
                    ->color('green')
                    ->visible(fn ($record) => $record->status === 'shipped')
                    ->action(function ($record) {
                        $record->update(['status' => 'delivered']);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark as Delivered')
                    ->modalDescription('This will mark the order as delivered.')
                    ->modalSubmitActionLabel('Mark as Delivered'),
                
                Tables\Actions\Action::make('mark_completed')
                    ->label('Completed')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'delivered')
                    ->action(function ($record) {
                        $record->update(['status' => 'completed']);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark as Completed')
                    ->modalDescription('This will mark the order as completed.')
                    ->modalSubmitActionLabel('Mark as Completed'),
                
                Tables\Actions\Action::make('mark_returned')
                    ->label('Returned')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('orange')
                    ->visible(fn ($record) => in_array($record->status, ['shipped', 'delivered']))
                    ->action(function ($record) {
                        $record->update(['status' => 'returned']);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark as Returned')
                    ->modalDescription('This will mark the order as returned.')
                    ->modalSubmitActionLabel('Mark as Returned'),
                
                Tables\Actions\Action::make('mark_refunded')
                    ->label('Refunded')
                    ->icon('heroicon-o-arrow-path')
                    ->color('red')
                    ->visible(fn ($record) => in_array($record->status, ['returned', 'delivered']))
                    ->action(function ($record) {
                        $record->update(['status' => 'refunded']);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark as Refunded')
                    ->modalDescription('This will mark the order as refunded.')
                    ->modalSubmitActionLabel('Mark as Refunded'),
                
                Tables\Actions\Action::make('cancel_order')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-mark')
                    ->color('secondary')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'confirmed', 'processing']))
                    ->action(function ($record) {
                        $record->update(['status' => 'cancelled']);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Order')
                    ->modalDescription('This will cancel the order. This action cannot be undone.')
                    ->modalSubmitActionLabel('Cancel Order'),
                    
            ])->icon('heroicon-o-arrow-path')
        ])
        ->bulkActions([
            Tables\Actions\BulkAction::make('export_orders')
                ->label('Export Orders')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->form([
                    Forms\Components\Select::make('format')
                        ->label('Export Format')
                        ->options([
                            'xlsx' => 'Excel (.xlsx)',
                            'csv' => 'CSV (.csv)',
                            // 'pdf' => 'PDF (.pdf)',
                        ])
                        ->default('xlsx')
                        ->required(),
                    Forms\Components\DatePicker::make('date_from')
                        ->label('From Date')
                        ->helperText('Optional: Export orders from this date'),
                    Forms\Components\DatePicker::make('date_to')
                        ->label('To Date')
                        ->helperText('Optional: Export orders until this date'),
                ])
                ->action(function (array $data, Collection $records) {
                    // Get the table's filtered query
                    $query = Order::query();
                    
                    // Apply additional date filters if provided
                    if ($data['date_from']) {
                        $query->whereDate('created_at', '>=', $data['date_from']);
                    }
                    if ($data['date_to']) {
                        $query->whereDate('created_at', '<=', $data['date_to']);
                    }
                    
                    // If specific records are selected, filter by them
                    if ($records->isNotEmpty()) {
                        $query->whereIn('id', $records->pluck('id'));
                    }
                    
                    $orders = $query->with(['user', 'lines'])->get();
                    
                    // Calculate statistics
                    $totalOrders = $orders->count();
                    $totalRevenue = $orders->sum('total');
                    $statusBreakdown = $orders->groupBy('status')->map->count();
                    
                    // Generate filename
                    $filename = 'orders_export_' . now()->format('Y-m-d_H-i-s');
                    
                    try {
                        if ($data['format'] === 'pdf') {
                            // Export as PDF
                            $pdfExport = new \App\Exports\OrdersPdfExport($orders, $totalOrders, $totalRevenue, $statusBreakdown);
                            return $pdfExport->export();
                        } else {
                            // Export as Excel/CSV
                            $excelExport = new \App\Exports\OrdersExcelExport($orders, $totalOrders, $totalRevenue, $statusBreakdown);
                            
                            if ($data['format'] === 'csv') {
                                return \Maatwebsite\Excel\Facades\Excel::download($excelExport, $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
                            } else {
                                return \Maatwebsite\Excel\Facades\Excel::download($excelExport, $filename . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
                            }
                        }
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Export Failed')
                            ->body('An error occurred while generating the export: ' . $e->getMessage())
                            ->send();
                        
                        // Log the error for debugging
                        Log::error('PDF Export Error: ' . $e->getMessage(), [
                            'trace' => $e->getTraceAsString(),
                            'data' => $data,
                            'orders_count' => $orders->count()
                        ]);
                    }
                })
                ->modalHeading('Export Orders')
                ->modalDescription('Export selected orders with comprehensive data and statistics.')
                ->modalSubmitActionLabel('Export Orders'),

            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\BulkAction::make('confirm_orders')
                    ->label('Confirm Orders')
                    ->icon('heroicon-o-check-circle')
                    ->color('blue')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            if (in_array($record->status, ['pending'])) {
                                $record->update(['status' => 'confirmed']);
                            }
                        });
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Orders')
                    ->modalDescription('This will mark selected orders as confirmed and ready for processing.')
                    ->modalSubmitActionLabel('Confirm Orders'),
                
                Tables\Actions\BulkAction::make('mark_processing')
                    ->label('Mark as Processing')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->color('yellow')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            if (in_array($record->status, ['pending', 'confirmed'])) {
                                $record->update(['status' => 'processing']);
                            }
                        });
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark Orders as Processing')
                    ->modalDescription('This will mark selected orders as being processed.')
                    ->modalSubmitActionLabel('Mark as Processing'),
                
                Tables\Actions\BulkAction::make('mark_shipped')
                    ->label('Mark as Shipped')
                    ->icon('heroicon-o-truck')
                    ->color('purple')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            if (in_array($record->status, ['confirmed', 'processing'])) {
                                $record->update(['status' => 'shipped']);
                            }
                        });
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark Orders as Shipped')
                    ->modalDescription('This will mark selected orders as shipped.')
                    ->modalSubmitActionLabel('Mark as Shipped'),
                
                Tables\Actions\BulkAction::make('mark_delivered')
                    ->label('Mark as Delivered')
                    ->icon('heroicon-o-home')
                    ->color('green')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            if (in_array($record->status, ['shipped'])) {
                                $record->update(['status' => 'delivered']);
                            }
                        });
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark Orders as Delivered')
                    ->modalDescription('This will mark selected orders as delivered.')
                    ->modalSubmitActionLabel('Mark as Delivered'),
                
                Tables\Actions\BulkAction::make('mark_completed')
                    ->label('Mark as Completed')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            if (in_array($record->status, ['delivered'])) {
                                $record->update(['status' => 'completed']);
                            }
                        });
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark Orders as Completed')
                    ->modalDescription('This will mark selected orders as completed.')
                    ->modalSubmitActionLabel('Mark as Completed'),
                
                Tables\Actions\BulkAction::make('mark_returned')
                    ->label('Mark as Returned')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('orange')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            if (in_array($record->status, ['shipped', 'delivered'])) {
                                $record->update(['status' => 'returned']);
                            }
                        });
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark Orders as Returned')
                    ->modalDescription('This will mark selected orders as returned.')
                    ->modalSubmitActionLabel('Mark as Returned'),
                
                Tables\Actions\BulkAction::make('mark_refunded')
                    ->label('Mark as Refunded')
                    ->icon('heroicon-o-arrow-path')
                    ->color('red')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            if (in_array($record->status, ['returned', 'delivered'])) {
                                $record->update(['status' => 'refunded']);
                            }
                        });
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark Orders as Refunded')
                    ->modalDescription('This will mark selected orders as refunded.')
                    ->modalSubmitActionLabel('Mark as Refunded'),
                
                Tables\Actions\BulkAction::make('cancel_orders')
                    ->label('Cancel Orders')
                    ->icon('heroicon-o-x-mark')
                    ->color('secondary')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            if (in_array($record->status, ['pending', 'confirmed', 'processing'])) {
                                $record->update(['status' => 'cancelled']);
                            }
                        });
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Orders')
                    ->modalDescription('This will cancel selected orders. This action cannot be undone.')
                    ->modalSubmitActionLabel('Cancel Orders'),
                
                Tables\Actions\DeleteBulkAction::make(),
                
            ]),
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make()
                ->label('Create Order')
                ->icon('heroicon-o-plus')
                ->modalHeading('Create Order')
                ->modalWidth('md'),
        ])
        ->defaultSort('created_at', 'desc')
        ->striped()
        ->paginated([10, 25, 50, 100]);
    }


    public static function getRelations(): array
    {
        return [
            OrderLinesRelationManager::class,
            \App\Filament\Resources\OrderResource\RelationManagers\DiscountsRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }


} 