<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqItemResource\Pages;
use App\Filament\Resources\FaqItemResource\RelationManagers;
use App\Models\FaqItem;
use App\Models\FaqCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FaqItemResource extends Resource
{
    protected static ?string $model = FaqItem::class;
    
    protected static ?string $navigationGroup = 'Content Management';
    
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FAQ Information')
                    ->schema([
                        Forms\Components\Select::make('faq_category_id')
                            ->label('Category')
                            ->options(FaqCategory::active()->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        
                        Forms\Components\TextInput::make('question')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\RichEditor::make('answer')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'link',
                                'bulletList',
                                'orderedList',
                            ]),
                    ]),
                
                Forms\Components\Section::make('Display Settings')
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active FAQ items will be displayed'),
                        
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured')
                            ->default(false)
                            ->helperText('Featured items may appear in special sections'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('question')
                    ->searchable()
                    ->limit(60)
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('view_count')
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
                Tables\Filters\SelectFilter::make('faq_category_id')
                    ->label('Category')
                    ->options(FaqCategory::active()->pluck('name', 'id')),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListFaqItems::route('/'),
            'create' => Pages\CreateFaqItem::route('/create'),
            'edit' => Pages\EditFaqItem::route('/{record}/edit'),
        ];
    }
}
