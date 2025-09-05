<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\OrderDiscount;
use App\Models\Product;
use App\Models\OrderLine;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea as FormsTextarea;
use Filament\Forms\Components\Repeater;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Forms\Get;
use Filament\Forms\Set;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Customer & Status')
                ->description('Order customer and status information.')
                ->schema([
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->nullable()
                        ->helperText('Select the customer for this order.'),
                    Select::make('status')
                        ->options(['pending'=>'Pending', 'confirmed'=>'Confirmed', 'processing'=>'Processing', 'shipped'=>'Shipped', 'delivered'=>'Delivered', 'returned'=>'Returned', 'refunded'=>'Refunded', 'cancelled'=>'Cancelled', 'completed'=>'Completed'])->required()->helperText('Order status.'),
                    Select::make('payment_method')
                        ->options([
                            'stripe' => 'Stripe',
                            'paypal' => 'PayPal',
                            'cod' => 'Cash on Delivery',
                        ])->nullable()->helperText('Payment method used.'),
                    Select::make('payment_status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed',
                            'refunded' => 'Refunded',
                        ])->required()->helperText('Payment status.'),
                    TextInput::make('payment_intent_id')->maxLength(255)->nullable()->helperText('Payment intent/reference ID.'),
                ])->columns(2),

            Section::make('Order Items')
                ->description('Manage order products. Total will be calculated automatically.')
                ->schema([
                    Repeater::make('order_lines')
                        ->schema([
                            Select::make('product_id')
                                ->label('Product')
                                ->options(Product::all()->pluck('name', 'id'))
                                ->searchable()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                    if ($state) {
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('product_name', $product->name);
                                            // Clear all dependent fields
                                            $set('variant', null);
                                            $set('variant_data', null);
                                            $set('sku', null);
                                            $set('price', null);
                                            // Set available variants if product has them
                                            if ($product->hasVariants() && $product->variants) {
                                                $set('has_variants', true);
                                            } else {
                                                $set('has_variants', false);
                                                $set('sku', $product->sku);
                                                $set('price', $product->price);
                                                $this->updateLineTotal($set, $get);
                                            }
                                        }
                                    } else {
                                        $set('has_variants', false);
                                        $set('sku', null);
                                        $set('price', null);
                                    }
                                }),
                            TextInput::make('product_name')
                                ->disabled()
                                ->dehydrated(true),
                            // Variant dropdown, only if product has variants
                            Select::make('variant')
                                ->label('Variant')
                                ->options(function (Get $get) {
                                    $productId = $get('product_id');
                                    if (!$productId) return [];
                                    $product = Product::find($productId);
                                    if (!$product || !$product->hasVariants() || !$product->variants) {
                                        return [];
                                    }
                                    $variants = [];
                                    foreach ($product->variants as $index => $variant) {
                                        $label = $variant['sku'];
                                        $variants[$index] = $label;
                                    }
                                    $converted = array_combine($variants, $variants);
                                    return $converted;
                                })
                                ->reactive()
                                ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                    $productId = $get('product_id');
                                    if ($productId) {
                                        $product = Product::find($productId);
                                
                                        if ($product && $product->variants && isset($state)) {
                                            $variant =  array_filter($product->variants, function($variant) use ($state) {
                                                return $variant['sku'] == $state;
                                            });
                                            $variant = array_values($variant)[0];
                                        ;
                                          
                         
                                            $set('price', $variant['price'] ?? null);
                                            $set('sku', $variant['sku'] ?? null);
                                            $set('variant_data', $variant);
                                            $this->updateLineTotal($set, $get);
                                        } else {
                                            $set('price', null);
                                            $set('sku', null);
                                            $set('variant_data', null);
                                        }
                                    }
                                })
                                ->helperText('Select product variant if available')
                                ->visible(fn (Get $get) => $get('has_variants') === true),
                            // SKU field, only if product has NO variants
                            TextInput::make('sku')
                                ->label('SKU')
                                ->disabled()
                                ->dehydrated(false)
                                ->visible(fn (Get $get) => $get('has_variants') === false),
                            // Price field, only show if SKU or variant is chosen
                            TextInput::make('price')
                                ->numeric()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    // Only update the line total here
                                    $this->updateLineTotal($set, $get);
                                }),
                            TextInput::make('quantity')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    // Only update the line total here
                                    $this->updateLineTotal($set, $get);
                                }),
                            TextInput::make('total')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->helperText('Line total (price × quantity)'),
                            TextInput::make('variant_data')
                                ->hidden()
                                ->dehydrated(true),
                            TextInput::make('has_variants')
                                ->hidden()
                                ->dehydrated(false),
                            TextInput::make('order_line_id')
                                ->hidden()
                                ->dehydrated(true),
                        ])
                        ->columns(6)
                        ->defaultItems(0)
                        ->reorderable(false)
                        ->addActionLabel('Add Product')
                        ->deleteAction(
                            fn ($action) => $action->label('Remove Product')
                        ),
                    \Filament\Forms\Components\Actions::make([
                        \Filament\Forms\Components\Actions\Action::make('finalize')
                            ->label('Finalize Order Lines')
                            ->action(function (\Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                                $this->updateOrderTotals($set, $get);
                            })
                            ->color('primary')
                            ->icon('heroicon-o-calculator')
                    ]),
                ]),

            Section::make('Order Summary')
                ->description('Order totals (calculated automatically from order lines and discounts).')
                ->schema([
                    TextInput::make('subtotal')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('Subtotal before discounts')
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            $this->updateOrderTotals($set, $get);
                        }),
                    TextInput::make('total_discount')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('Total discounts applied')
                        ->default(fn ($record) => $record ? $record->total_discount : 0),
                    TextInput::make('total')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('Final total after discounts')
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            $this->updateOrderTotals($set, $get);
                        }),
                    TextInput::make('currency')->maxLength(10)->default('USD')->helperText('Currency code.'),
                ])->columns(2),

            Section::make('Shipping & Tracking')
                ->description('Shipping method and tracking information.')
                ->schema([
                    TextInput::make('shipping_method')->maxLength(255)->nullable()->helperText('Shipping method used.'),
                    TextInput::make('tracking')->maxLength(255)->nullable()->helperText('Tracking number or URL.'),
                ])->columns(2),

            Section::make('Shipping Address')
                ->description('Enter the shipping address for this order.')
                ->schema([
                    TextInput::make('shipping_address.first_name')
                        ->label('First Name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('shipping_address.last_name')
                        ->label('Last Name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('shipping_address.email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    TextInput::make('shipping_address.phone')
                        ->label('Phone')
                        ->tel()
                        ->maxLength(255),
                    TextInput::make('shipping_address.company')
                        ->label('Company')
                        ->maxLength(255),
                    TextInput::make('shipping_address.address')
                        ->label('Address Line')
                        ->required()
                        ->maxLength(255),
                   
                    TextInput::make('shipping_address.city')
                        ->label('City')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('shipping_address.state')
                        ->label('State/Province')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('shipping_address.zip')
                        ->label('Postal Code')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('shipping_address.country')
                        ->label('Country')
                        ->required()
                        ->default('United States')
                        ->maxLength(255),
                ])->columns(2),

            Section::make('Billing Address')
                ->description('Enter the billing address for this order.')
                ->schema([
                    TextInput::make('billing_address.first_name')
                        ->label('First Name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('billing_address.last_name')
                        ->label('Last Name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('billing_address.email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    TextInput::make('billing_address.phone')
                        ->label('Phone')
                        ->tel()
                        ->maxLength(255),
                    TextInput::make('billing_address.company')
                        ->label('Company')
                        ->maxLength(255),
                    TextInput::make('billing_address.address')
                        ->label('Address Line')
                        ->required()
                        ->maxLength(255),
                  
                    TextInput::make('billing_address.city')
                        ->label('City')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('billing_address.state')
                        ->label('State/Province')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('billing_address.zip')
                        ->label('Postal Code')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('billing_address.country')
                        ->label('Country')
                        ->required()
                        ->default('United States')
                        ->maxLength(255),
                ])->columns(2),

            Section::make('Order Notes')
                ->description('Add any special notes or instructions for this order.')
                ->schema([
                    FormsTextarea::make('notes')
                        ->nullable()
                        ->helperText('Order notes or special instructions.')
                        ->rows(4)
                        ->columnSpanFull(),
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
                    TextInput::make('amount')
                        ->label('Discount Amount')
                        ->numeric()
                        ->required()
                        ->helperText('Enter the discount amount'),
                    Select::make('type')
                        ->label('Discount Type')
                        ->options([
                            'manual' => 'Manual Discount',
                            'coupon' => 'Coupon',
                            'loyalty' => 'Loyalty',
                            'promotion' => 'Promotion',
                        ])
                        ->default('manual')
                        ->required(),
                    Textarea::make('reason')
                        ->label('Reason')
                        ->helperText('Optional reason for the discount')
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    $this->record->discounts()->create([
                        'amount' => $data['amount'],
                        'type' => $data['type'],
                        'reason' => $data['reason'],
                        'applied_by' => Auth::id(),
                    ]);

                    // Recalculate order total
                    $this->record->recalculateTotal();

                    Notification::make()
                        ->success()
                        ->title('Discount added successfully')
                        ->body('The discount has been added to the order.')
                        ->send();
                })
                ->modalHeading('Add Discount to Order')
                ->modalDescription('Add a discount to this order. This will be tracked with your user information.')
                ->modalSubmitActionLabel('Add Discount'),

            Action::make('recalculate_total')
                ->label('Recalculate Total')
                ->icon('heroicon-o-calculator')
                ->action(function (): void {
                    $this->record->recalculateTotal();
                    Notification::make()
                        ->success()
                        ->title('Order total recalculated')
                        ->body('The order total has been recalculated.')
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Recalculate Order Total')
                ->modalDescription('This will recalculate the order total based on current order lines and discounts.')
                ->modalSubmitActionLabel('Recalculate'),
        ];
    }

    protected function updateLineTotal(Set $set, Get $get): void
    {
        $price = (float)($get('price') ?? 0);
        $quantity = (int)($get('quantity') ?? 1);
        $total = $price * $quantity;
        $set('total', $total);
    }

    protected function updateOrderTotals(Set $set, Get $get): void
    {
        $subtotal = 0;
        $total = 0;

        $orderLines = $get('order_lines');
        if (is_array($orderLines)) {
            foreach ($orderLines as $line) {
                if (isset($line['total']) && is_numeric($line['total'])) {
                    $subtotal += (float)$line['total'];
                    $total += (float)$line['total'];
                }
            }
        }

        $set('subtotal', $subtotal);
        $set('total', $total);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load existing order lines
        $orderLines = [];
        foreach ($this->record->lines as $line) {
            $orderLines[] = [
                'order_line_id' => $line->id,
                'product_id' => $line->product_id,
                'product_name' => $line->product_name,
                'sku' => $line->sku,
                'price' => $line->price,
                'quantity' => $line->quantity,
                'total' => $line->total,
                'variant' => $line->variant ? $line->variant['sku'] : null,
                'variant_data' => $line->variant,
                'has_variants' => $line->variant ? true : false,
            ];
        }
        
        $data['order_lines'] = $orderLines;
        $data['subtotal'] = $this->record->subtotal;
        $data['total_discount'] = $this->record->total_discount;
        $data['total'] = $this->record->final_total;
        
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Store previous values
        // $previousStatus = $this->record->status;
        // $previousShippingMethod = $this->record->shipping_method;

        // Update the order
        $this->record->update([
            'user_id' => $data['user_id'],
            'status' => $data['status'],
            'payment_method' => $data['payment_method'],
            'payment_status' => $data['payment_status'],
            'payment_intent_id' => $data['payment_intent_id'],
            'currency' => $data['currency'],
            'shipping_method' => $data['shipping_method'],
            'tracking' => $data['tracking'],
            'shipping_address' => $data['shipping_address'],
            'billing_address' => $data['billing_address'],
            'notes' => $data['notes'],
        ]);

        // Handle order lines
        if (isset($data['order_lines']) && is_array($data['order_lines'])) {
            // Get existing line IDs
            $existingLineIds = $this->record->lines->pluck('id')->toArray();
            $updatedLineIds = [];
            
            foreach ($data['order_lines'] as $lineData) {
                if (isset($lineData['product_id']) && isset($lineData['price']) && isset($lineData['quantity'])) {
                    $product = Product::find($lineData['product_id']);
                    
                    $sku = null;
                    $variant = null;
                    if (isset($lineData['variant'])) {
                        $variant = array_filter($product->variants, function($variant) use ($lineData) {
                            return $variant['sku'] == $lineData['variant'];
                        });
                        $variantArray = array_values($variant);
                        $variant = $variantArray[0] ?? null;
                        $sku = $variant['sku'] ?? null;
                    }
                    if (!$sku) {
                        $sku = $product->sku;
                    }
                    
                    $lineDataToSave = [
                        'product_id' => $lineData['product_id'],
                        'product_name' => $lineData['product_name'],
                        'sku' => $sku,
                        'price' => (float)$lineData['price'],
                        'quantity' => (int)$lineData['quantity'],
                        'total' => (float)$lineData['price'] * (int)$lineData['quantity'],
                        'variant' => $variant,
                    ];
                    
                    // Update existing line or create new one
                    if (isset($lineData['order_line_id']) && $lineData['order_line_id']) {
                        $this->record->lines()->where('id', $lineData['order_line_id'])->update($lineDataToSave);
                        $updatedLineIds[] = $lineData['order_line_id'];
                    } else {
                        $newLine = $this->record->lines()->create($lineDataToSave);
                        $updatedLineIds[] = $newLine->id;
                    }
                }
            }
            
            // Delete lines that were removed
            $linesToDelete = array_diff($existingLineIds, $updatedLineIds);
            if (!empty($linesToDelete)) {
                $this->record->lines()->whereIn('id', $linesToDelete)->delete();
            }
        }

        // Recalculate total
        $this->record->recalculateTotal();

        // Send emails if status or shipping method changed
        // $orderEmailService = app()->make(\App\Services\OrderEmailService::class);
        // if ($previousStatus !== $data['status']) {
        //     $orderEmailService->sendOrderStatusUpdate($this->record, $previousStatus, $data['status']);
        // }
        // if ($previousShippingMethod !== $data['shipping_method'] && !empty($data['shipping_method'])) {
        //     $orderEmailService->sendShippingConfirmation($this->record);
        // }
        
        return $record;
    }

    protected function afterSave(): void
    {
        // Recalculate total after any changes
        $this->record->recalculateTotal();
    }
} 