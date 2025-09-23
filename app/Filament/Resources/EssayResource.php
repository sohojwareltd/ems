<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EssayResource\Pages;
use App\Filament\Resources\EssayResource\RelationManagers;
use App\Models\Essay;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
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

                            Forms\Components\Select::make('resource_type_id')
                                ->label('Resource')
                                ->relationship('resource', 'title')
                                // ->searchable()
                                ->required()
                                ->helperText('Assign a resource for better organization.'),
                            Forms\Components\Select::make('qualiification_id')
                                ->label('Qualiification')
                                ->relationship('qualiification', 'title')
                                // ->searchable()
                                ->required()
                                ->helperText('Assign a qualiification for better organization.'),
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


                            // Forms\Components\RichEditor::make('description')
                            //     ->required()
                            //     ->columnSpanFull(),

                            Forms\Components\Select::make('year')
                                ->required()
                                ->options([
                                    '2019' => '2019',
                                    '2020' => '2020',
                                    '2021' => '2021',
                                    '2022' => '2022',
                                    '2023' => '2023',
                                    '2024' => '2024',
                                ])
                                ->label('Year'),

                            Forms\Components\Select::make('month')
                                ->required()
                                ->options([
                                    'January' => 'January',
                                    'June' => 'June',
                                    'November' => 'November',
                                ])
                                ->label('Month'),

                            Forms\Components\Select::make('marks')
                                ->required()
                                ->options([
                                    '6' => '6 Marks',
                                    '9' => '9 Marks',
                                    '12' => '12 Marks',
                                ])
                                ->label('Marks'),

                            Forms\Components\Select::make('topic_id')
                                ->relationship('topic', 'name')
                                ->searchable()
                                ->required()
                                ->label('Topic'),

                            Forms\Components\Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'active' => 'Active',
                                    'archived' => 'Archived',
                                ])
                                ->default('draft')
                                ->required()
                                ->helperText('Set the product status.'),


                        ]),
                    Forms\Components\Tabs\Tab::make('Media')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            // Thumbnail upload

                            // Forms\Components\FileUpload::make('thumbnail')
                            //     ->label('Thumbnail')
                            //     ->image()
                            //     ->nullable()
                            //     ->directory('products/thumbnails')
                            //     ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                            //     ->maxSize(5120),


                            // Forms\Components\FileUpload::make('gallery')
                            //     ->label('Gallery Images')
                            //     ->image()
                            //     ->multiple()
                            //     ->directory('products/gallery')
                            //     ->nullable()
                            //     ->helperText('Additional images for the product gallery.')
                            //     ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                            //     ->maxSize(2048),


                            Forms\Components\FileUpload::make('file')
                                ->label('PDF File')
                                ->directory('products/essay')
                                ->acceptedFileTypes(['application/pdf'])
                                ->required()
                                ->maxSize(5120),


                            Forms\Components\FileUpload::make('ppt_file')
                                ->label('PowerPoint File')
                                ->directory('products/powerpoints'),
                        ]),
                    // Forms\Components\Tabs\Tab::make('SEO')
                    //     ->icon('heroicon-o-magnifying-glass')
                    //     ->schema([
                    //         Forms\Components\TextInput::make('meta_title')
                    //             ->label('Meta Title')
                    //             ->maxLength(255),
                    //         Forms\Components\Textarea::make('meta_description')
                    //             ->label('Meta Description'),
                    //         Forms\Components\TextInput::make('meta_keywords')
                    //             ->label('Meta Keywords')
                    //             ->maxLength(255),
                    //         Forms\Components\TextInput::make('tags')
                    //             ->label('Tags')
                    //             ->helperText('Enter tags separated by commas.'),
                    //     ]),
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
                Tables\Columns\TextColumn::make('year')
                    ->sortable(),

                Tables\Columns\TextColumn::make('month')
                    ->sortable(),

                Tables\Columns\TextColumn::make('marks')
                    ->sortable(),

                Tables\Columns\TextColumn::make('topic.name')
                    ->label('Topic')
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\TextColumn::make('category_id')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('brand_id')
                //     ->numeric()
                //     ->sortable(),

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
