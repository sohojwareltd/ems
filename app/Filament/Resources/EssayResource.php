<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EssayResource\Pages;
use App\Filament\Resources\EssayResource\RelationManagers;
use App\Models\Essay;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EssayResource extends Resource
{
    protected static ?string $model = Essay::class;
    protected static ?string $navigationLabel = 'Model Essays';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 7;

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

                            Forms\Components\Select::make('resource_id')
                                ->label('Resource')
                                ->relationship('resource', 'title')
                                ->searchable()
                                ->helperText('Assign a resource for better organization.'),
                             Forms\Components\Select::make('qualiification_id')
                                ->label('Qualiification')
                                ->relationship('qualiification', 'title')
                                ->searchable()
                                ->helperText('Assign a qualiification for better organization.'),
                             Forms\Components\Select::make('subject_id')
                                ->label('Subject')
                                ->relationship('subject', 'title')
                                ->searchable()
                                ->helperText('Assign a subject for better organization.'),
                             Forms\Components\Select::make('examboard_id')
                                ->label('Examboard')
                                ->relationship('examboard', 'title')
                                ->searchable()
                                ->helperText('Assign a examboard for better organization.'),
                                
                            Forms\Components\Textarea::make('description')
                                ->helperText('Detailed product description.'),
                           
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
                   
                    Forms\Components\Tabs\Tab::make('Media')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            // Thumbnail upload
                            Forms\Components\FileUpload::make('thumbnail')
                               ->label('Thumbnail')
                                ->directory('products/gallery/pdf')
                                ->acceptedFileTypes(['application/pdf'])
                                ->maxSize(5120),
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
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('views')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('thumbnail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('compare_at_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_per_item')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('track_quantity')
                    ->boolean(),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
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
            'index' => Pages\ListEssays::route('/'),
            'create' => Pages\CreateEssay::route('/create'),
            'edit' => Pages\EditEssay::route('/{record}/edit'),
        ];
    }
}
