<?php

namespace App\Filament\Resources;

use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ResourcePermissionTrait;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    use ResourcePermissionTrait;
    protected static ?string $model = Product::class;
    protected static ?string $navigationLabel = 'Products';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Product Tabs')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('General')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\Toggle::make('has_variants')->label('Has Variants')->default(false)
                                ->live()
                                ->visible(fn($get) => !$get('is_digital'))
                                ->helperText('Enable if this product has multiple variants.')->columnSpanFull(),
                            Forms\Components\Toggle::make('is_digital')->label('Is Digital')->default(false)
                                ->live()
                                ->visible(fn($get) => !$get('has_variants'))
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state) {
                                        $audioCategoryId = \App\Models\Category::where('slug', 'audiobooks')->value('id');
                                        if ($audioCategoryId) {
                                            $set('category_id', $audioCategoryId);
                                        }
                                    } else {
                                        $set('category_id', null);
                                    }
                                })
                                ->helperText('Enable if this product is digital.')->columnSpanFull(),
                            Forms\Components\TextInput::make('name')->required()->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $state, callable $set) {
                                    $set('slug', Str::slug($state));
                                })
                                ->helperText('Enter the product name as it will appear to customers.'),
                            Forms\Components\TextInput::make('slug')->required()->maxLength(255)
                                ->helperText('Unique URL slug for the product. Auto-generated from the name.'),


                            Forms\Components\Textarea::make('description')
                                ->helperText('Detailed product description.'),
                            Forms\Components\Select::make('category_id')
                                ->label('Category')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->nullable()
                                ->disabled(fn($get) => $get('is_digital'))
                                ->helperText(fn($get) => $get('is_digital') 
                                    ? 'Automatically set to Audiobooks for digital products.' 
                                    : 'Assign a category for better organization.')
                                ->afterStateHydrated(function ($state, $set, $get) {
                                    if ($get('is_digital')) {
                                        $audioCategoryId = \App\Models\Category::where('slug', 'audiobooks')->value('id');
                                        if ($audioCategoryId) {
                                            $set('category_id', $audioCategoryId);
                                        }
                                    }
                                })
                                ->dehydrateStateUsing(function ($state, $get) {
                                    if ($get('is_digital')) {
                                        $audioCategoryId = \App\Models\Category::where('slug', 'audiobooks')->value('id');
                                        return $audioCategoryId;
                                    }
                                    return $state;
                                }),
                            Forms\Components\Select::make('brand_id')
                                ->label('Brand')
                                ->relationship('brand', 'name')
                                ->searchable()
                                ->nullable()
                                ->helperText('Select the product brand.'),
                            Forms\Components\Select::make('audio_books')
                                ->label('Attach Audio Books')
                                ->relationship('audioBooks', 'title')
                                ->multiple()
                                ->visible(fn($get) => $get('is_digital'))
                                ->helperText('Attach one or more audio books to this product.'),
                            Forms\Components\Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'active' => 'Active',
                                    'archived' => 'Archived',
                                ])
                                ->default('draft')
                                ->required()
                                ->helperText('Set the product status.')
                        ]),
                    Forms\Components\Tabs\Tab::make('Pricing')
                        ->icon('heroicon-o-currency-dollar')
                        ->visible(fn($get) => !$get('has_variants'))
                        ->schema([
                            Forms\Components\TextInput::make('price')->label('Price')->numeric()->required()
                                ->prefix('$')->helperText('Current selling price.'),
                            Forms\Components\TextInput::make('compare_at_price')->label('Compare at Price')->numeric()
                                ->prefix('$')->helperText('Original price for showing discounts.'),
                            Forms\Components\TextInput::make('cost_per_item')->label('Cost per Item')->numeric()
                                ->prefix('$')->helperText('Internal cost for profit calculation.'),
                        ]),
                    Forms\Components\Tabs\Tab::make('Inventory')
                        ->icon('heroicon-o-archive-box')
                        ->visible(fn($get) => !$get('has_variants')  && !$get('is_digital'))
                        ->schema([
                            Forms\Components\TextInput::make('sku')->label('SKU')->maxLength(255)
                                ->helperText('Stock Keeping Unit for inventory tracking.'),
                            Forms\Components\TextInput::make('barcode')->label('Barcode')->maxLength(255)
                                ->helperText('Product barcode (UPC, EAN, etc).'),
                            Forms\Components\TextInput::make('stock')->label('Stock')->numeric()->default(0)
                                ->helperText('Available quantity in stock.'),
                            Forms\Components\Toggle::make('track_quantity')->label('Track Quantity')->default(true)
                                ->helperText('Enable to track inventory quantity.'),
                            Forms\Components\TextInput::make('weight')->label('Weight (kg)')->numeric()
                                ->helperText('Weight for shipping calculation.'),
                            Forms\Components\TextInput::make('height')->label('Height (cm)')->numeric(),
                            Forms\Components\TextInput::make('width')->label('Width (cm)')->numeric(),
                            Forms\Components\TextInput::make('length')->label('Length (cm)')->numeric(),
                        ]),
                    Forms\Components\Tabs\Tab::make('Media')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Forms\Components\FileUpload::make('thumbnail')
                                ->label('Thumbnail')
                                ->image()
                                ->directory('products/thumbnails')
                                ->nullable()
                                ->helperText('Main product image (shown in listings).')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                                ->maxSize(2048),
                            Forms\Components\FileUpload::make('gallery')
                                ->label('Gallery Images')
                                ->image()
                                ->multiple()
                                ->directory('products/gallery')
                                ->nullable()
                                ->helperText('Additional images for the product gallery.')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                                ->maxSize(2048),
                        ]),
                    Forms\Components\Tabs\Tab::make('Variants')
                        ->icon('heroicon-o-squares-2x2')
                        ->visible(fn($get) => $get('has_variants'))
                        ->schema([
                            Forms\Components\Repeater::make('variants')
                                ->label('Product Variants')
                                ->helperText('Add different options like size, color, etc.')
                                ->schema([
                                    Forms\Components\Section::make('Variant Details')
                                        ->schema([
                                            Forms\Components\TextInput::make('sku')->label('SKU')->required(),
                                            Forms\Components\TextInput::make('barcode')->label('Barcode')->maxLength(255)
                                                ->helperText('Product barcode (UPC, EAN, etc).'),
                                            Forms\Components\KeyValue::make('attributes')
                                                ->label('Attributes (e.g. color, size)')
                                                ->keyLabel('Attribute')
                                                ->valueLabel('Value')
                                                ->required()
                                                ->helperText('Specify attributes like color, size, etc.'),
                                            Forms\Components\Fieldset::make('Pricing & Inventory')
                                                ->schema([
                                                    Forms\Components\Toggle::make('track_quantity')->label('Track Quantity')->default(true)
                                                        ->helperText('Enable to track inventory quantity.')->columnSpanFull(),
                                                    Forms\Components\TextInput::make('price')->label('Price')->numeric()->required()
                                                        ->prefix('$')->helperText('Current selling price.'),
                                                    Forms\Components\TextInput::make('compare_at_price')->label('Compare at Price')->numeric()
                                                        ->prefix('$')->helperText('Original price for showing discounts.'),
                                                    Forms\Components\TextInput::make('cost_per_item')->label('Cost per Item')->numeric()
                                                        ->prefix('$')->helperText('Internal cost for profit calculation.'),
                                                    Forms\Components\TextInput::make('stock')->label('Stock')->numeric()->required(),
                                                ])->columns(2),
                                            Forms\Components\Fieldset::make('Dimensions')
                                                ->label('Dimensions (Physical dimensions for shipping)')
                                                ->schema([
                                                    Forms\Components\Placeholder::make('dimensions_help')
                                                        ->content('Physical dimensions for shipping.'),
                                                    Forms\Components\TextInput::make('weight')->label('Weight (kg)')->numeric(),
                                                    Forms\Components\TextInput::make('height')->label('Height (cm)')->numeric(),
                                                    Forms\Components\TextInput::make('width')->label('Width (cm)')->numeric(),
                                                    Forms\Components\TextInput::make('length')->label('Length (cm)')->numeric(),
                                                ])->columns(4),
                                            Forms\Components\FileUpload::make('image')->label('Variant Image')->image()->directory('products/variants')->nullable()
                                                ->helperText('Image specific to this variant.'),
                                        ]),
                                ])
                                ->addActionLabel('Add Variant'),
                        ])->maxWidth('full')->columns(1)->columnSpanFull(),
                    Forms\Components\Tabs\Tab::make('SEO')
                        ->icon('heroicon-o-magnifying-glass')
                        ->schema([
                            Forms\Components\TextInput::make('meta_title')->label('Meta Title')->maxLength(255),
                            Forms\Components\Textarea::make('meta_description')->label('Meta Description'),
                            Forms\Components\TextInput::make('meta_keywords')->label('Meta Keywords')->maxLength(255),
                            Forms\Components\TextInput::make('tags')->label('Tags')

                                ->helperText('Enter tags separated by commas.'),
                        ]),
                ])->maxWidth('full')
                ->columns(2)
                ->columnSpanFull()


        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('thumbnail')->label('Thumbnail')->size(40),
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('price')
                ->label('Price')
                ->sortable()
                ->formatStateUsing(function ($state, $record) {
                    if (method_exists($record, 'hasVariants') && $record->hasVariants() && is_array($record->variants) && count($record->variants) > 0) {
                        $min = $record->getMinPrice();
                        $max = $record->getMaxPrice();
                        if ($min == $max) {
                            return '<span class="inline-badge badge badge-success">$' . number_format($min, 2) . '</span>';
                        } else {
                            return '<span class="inline-badge badge badge-info">$' . number_format($min, 2) . ' - $' . number_format($max, 2) . '</span>';
                        }
                    } else {
                        return '<span class="inline-badge badge badge-success">$' . number_format($record->price, 2) . '</span>';
                    }
                })
                ->html()
                ->tooltip('Shows the price or price range for variants'),
            Tables\Columns\TextColumn::make('stock')
                ->label('Stock')
                ->sortable()
                ->formatStateUsing(function ($state, $record) {
                    $stock = $record->getStock();
                    if ($record->is_digital) {
                        return '<span class="inline-badge badge badge-success">Digital</span>';
                    } elseif ($record->hasVariants()) {
                        return '<span class="inline-badge badge badge-success">' . $stock . '</span>';
                    } elseif ($record->hasVariants() == false && $record->getStock() > 0) {
                        return '<span class="inline-badge badge badge-danger">'.$stock.'</span>';
                    } else {
                        return '<span class="inline-badge badge badge-danger">Out of Stock</span>';
                    }
                 
                })
                ->html()
                ->tooltip('Shows total stock (sum of all variants if applicable)'),
            Tables\Columns\TextColumn::make('status')->sortable(),
            Tables\Columns\TextColumn::make('is_digital')->label('Type')->sortable()->formatStateUsing(function ($state) {
                return $state ? 'Digital' : 'Physical';
            }),
            Tables\Columns\TextColumn::make('has_variants')->label('Has Variants')->sortable()->formatStateUsing(function ($state) {
                return $state ? 'Yes' : 'No';
            }),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'draft' => 'Draft',
                    'active' => 'Active',
                    'archived' => 'Archived',
                ]),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
