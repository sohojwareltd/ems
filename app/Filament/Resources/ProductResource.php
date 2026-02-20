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
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    use ResourcePermissionTrait;

    protected static ?string $model = Product::class;
    protected static ?string $navigationLabel = 'Products';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 6;

    public static function getNavigationBadge(): ?string
    {
        return (string) Product::query()->count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Product Tabs')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('General')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $state, callable $set) {
                                    $set('slug', Str::slug($state));
                                })
                                ->helperText('Enter the product name as it will appear to customers.'),
                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                ->helperText('Unique URL slug for the product. Auto-generated from the name.'),
                            Forms\Components\TextInput::make('price')
                                ->label('Price')
                                ->numeric()
                                ->required()
                                ->prefix('$')
                                ->helperText('Current selling price.'),
                            Forms\Components\TextInput::make('sort_order')
                                ->label('Sort')
                                ->numeric()
                                ->default(0)
                                ->helperText('Lower numbers appear first.'),
                            // Forms\Components\Select::make('category_id')
                            //     ->label('Category')
                            //     ->relationship('category', 'name')
                            //     ->searchable()
                            //     ->nullable()
                            //     ->helperText('Assign a category for better organization.'),
                            Forms\Components\Select::make('resource_id')
                                ->label('Resource')
                                ->relationship('resource', 'title')
                                // ->searchable()
                                ->required()
                                ->helperText('Assign a resource for better organization.'),
                            Forms\Components\Select::make('qualiification_id')
                                ->label('Qualification')
                                ->relationship('qualiification', 'title')
                                // ->searchable()
                                ->required()
                                ->helperText('Assign a qualification for better organization.'),
                            Forms\Components\Select::make('subject_id')
                                ->label('Subject')
                                ->relationship('subject', 'title')
                                // ->searchable()
                                ->required()
                                ->helperText('Assign a subject for better organization.'),
                            Forms\Components\Select::make('examboard_id')
                                ->label('Examboard')
                                ->relationship('examboard', 'title')
                                // ->searchable()
                                ->required()
                                ->helperText('Assign a examboard for better organization.'),

                            Forms\Components\Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'active' => 'Active',
                                    'archived' => 'Archived',
                                ])
                                ->default('draft')
                                ->required()
                                ->helperText('Set the product status.'),
                            Toggle::make('is_featured')
                                ->label('Featured Product')
                                ->helperText('Mark this product as featured on the homepage or spotlight areas.')
                                ->default(false),
                            Forms\Components\RichEditor::make('description')
                                ->columnSpanFull()
                                ->helperText('Detailed product description.'),
                        ]),
                    // Forms\Components\Tabs\Tab::make('Pricing')
                    //     ->icon('heroicon-o-currency-dollar')
                    //     ->schema([
                    //         Forms\Components\TextInput::make('price')
                    //             ->label('Price')
                    //             ->numeric()
                    //             ->required()
                    //             ->prefix('$')
                    //             ->helperText('Current selling price.'),
                    //         Forms\Components\TextInput::make('compare_at_price')
                    //             ->label('Compare at Price')
                    //             ->numeric()
                    //             ->prefix('$')
                    //             ->helperText('Original price for showing discounts.'),
                    //         Forms\Components\TextInput::make('cost_per_item')
                    //             ->label('Cost per Item')
                    //             ->numeric()
                    //             ->prefix('$')
                    //             ->helperText('Internal cost for profit calculation.'),
                    //     ]),
                    Forms\Components\Tabs\Tab::make('Media')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            // Thumbnail upload
                            Forms\Components\FileUpload::make('thumbnail')
                                ->label('Thumbnail')
                                ->image()
                                ->nullable()
                                ->directory('products/thumbnails')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                                ->maxSize(5120),


                            Forms\Components\FileUpload::make('gallery')
                                ->label('Gallery Images')
                                ->image()
                                ->multiple()
                                ->directory('products/gallery')
                                ->nullable()
                                ->helperText('Additional images for the product gallery.')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                                ->maxSize(2048),

                            // File Type Selector
                            // Forms\Components\Select::make('file_type')
                            //     ->label('Select File Type')
                            //     ->options([
                            //         'pdf' => 'PDF',
                            //         'ppt' => 'PowerPoint',
                            //     ])
                            //     ->reactive()
                            //     ->required()
                            //     ->dehydrated(false) // DB-তে save হবে না
                            //     ->helperText('Choose what type of file you want to upload.'),

                            // PDF Upload (only when PDF is selected)




                            Forms\Components\FileUpload::make('pdf_file')
                                ->label('PDF File')
                                ->directory('products/gallery/pdf')
                                ->acceptedFileTypes(['application/pdf']),
                            // PowerPoint Upload (only when PPT is selected)
                            Forms\Components\FileUpload::make('ppt_file')
                                ->label('PPT File')
                                ->directory('products/gallery/ppt')
                                ->acceptedFileTypes([
                                    'application/vnd.ms-powerpoint', // .ppt
                                    'application/vnd.openxmlformats-officedocument.presentationml.presentation', // .pptx
                                ]),


                            Forms\Components\FileUpload::make('zip_file')
                                ->label('Zip File')
                                ->directory('products/gallery/zip'),
                        ]),
                    Forms\Components\Tabs\Tab::make('SEO')
                        ->icon('heroicon-o-magnifying-glass')
                        ->schema([
                            Forms\Components\TextInput::make('meta_title')
                                ->label('Meta Title')
                                ->maxLength(255),
                            Forms\Components\Textarea::make('meta_description')
                                ->label('Meta Description'),
                            Forms\Components\TextInput::make('meta_keywords')
                                ->label('Meta Keywords')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('tags')
                                ->label('Tags')
                                ->helperText('Enter tags separated by commas.'),
                        ]),
                ])
                ->maxWidth('full')
                ->columns(2)
                ->columnSpanFull()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            // Tables\Columns\TextColumn::make('id')
            //     ->label('ID')
            //     ->sortable(),
            Tables\Columns\ImageColumn::make('thumbnail')->label('Thumbnail')->size(40),
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('price')
                ->label('Price')
                ->sortable()
                ->formatStateUsing(
                    fn($state, $record) =>
                    '<span class="inline-badge badge badge-success">$' . number_format($record->price, 2) . '</span>'
                )
                ->html()
                ->tooltip('Shows the price'),
            Tables\Columns\TextColumn::make('sort_order')
                ->label('Sort')
                ->sortable(),
            Tables\Columns\TextColumn::make('status')->sortable(),
            Tables\Columns\ToggleColumn::make('is_featured')
                ->label('Featured')
                ->sortable(),
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
        return [];
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
