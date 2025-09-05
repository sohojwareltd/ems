<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Product;
use App\Models\OrderLine;
use App\Models\ShippingMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Customer & Status')
                ->description('Select the customer and set the order status and payment details.')
                ->schema([
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->nullable()
                        ->helperText('Select the customer for this order.'),
                    Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed',
                            'refunded' => 'Refunded',
                            'cancelled' => 'Cancelled',
                        ])->required()->helperText('Order status.'),
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
                ->description('Add products to the order. Total will be calculated automatically.')
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
                        ])
                        ->columns(6)
                        ->defaultItems(1)
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
                ->description('Order totals and additional information.')
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
                    TextInput::make('total')
                        ->numeric()
                        ->required()
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('Final total')
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            $this->updateOrderTotals($set, $get);
                        }),
                    TextInput::make('currency')->maxLength(10)->default('USD')->helperText('Currency code.'),
                ])->columns(2),

            Section::make('Shipping & Tracking')
                ->description('Shipping method and tracking information.')
                ->schema([
                    Select::make('shipping_method')
                        ->options(ShippingMethod::all()->pluck('name', 'id'))
                        ->searchable()
                        ->nullable()
                        ->helperText('Shipping method used.'),
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
                        ->label('Address')
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
                    \Filament\Forms\Components\Actions::make([
                        \Filament\Forms\Components\Actions\Action::make('same_as_shipping')
                            ->label('Same as Shipping Address')
                            ->icon('heroicon-o-document-duplicate')
                            ->color('primary')
                            ->action(function (\Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                                $shippingAddress = $get('shipping_address');
                                if ($shippingAddress) {
                                    $set('billing_address.first_name', $shippingAddress['first_name'] ?? '');
                                    $set('billing_address.last_name', $shippingAddress['last_name'] ?? '');
                                    $set('billing_address.email', $shippingAddress['email'] ?? '');
                                    $set('billing_address.phone', $shippingAddress['phone'] ?? '');
                                    $set('billing_address.company', $shippingAddress['company'] ?? '');
                                    $set('billing_address.address', $shippingAddress['address'] ?? '');
                                    $set('billing_address.city', $shippingAddress['city'] ?? '');
                                    $set('billing_address.state', $shippingAddress['state'] ?? '');
                                    $set('billing_address.zip', $shippingAddress['zip'] ?? '');
                                    $set('billing_address.country', $shippingAddress['country'] ?? '');
                                }
                            })
                            ->requiresConfirmation()
                            ->modalHeading('Copy Shipping Address')
                            ->modalDescription('This will copy all shipping address fields to the billing address. Any existing billing address information will be overwritten.')
                            ->modalSubmitActionLabel('Copy Address')
                    ])->columnSpanFull(),
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
                        ->label('Address')
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
                ->description('Additional notes or special instructions for this order.')
                ->schema([
                    Textarea::make('notes')
                        ->label('Notes')
                        ->nullable()
                        ->helperText('Order notes or special instructions.')
                        ->rows(3),
                ]),
        ]);
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

    protected function handleRecordCreation(array $data): Model
    {
        // Create the order first
        $order = static::getModel()::create([
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
            'total' => 0, // Will be calculated after lines are added
        ]);

   
        // Add order lines
        if (isset($data['order_lines']) && is_array($data['order_lines'])) {
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
                    
                    $order->lines()->create([
                        'product_id' => $lineData['product_id'],
                        'product_name' => $lineData['product_name'],
                        'sku' => $sku,
                        'price' => (float)$lineData['price'],
                        'quantity' => (int)$lineData['quantity'],
                        'total' => (float)$lineData['price'] * (int)$lineData['quantity'],
                        'variant' => $variant,
                    ]);
                }
            }
        }

        // Calculate and update total
        $order->recalculateTotal();

        return $order;
    }
} 