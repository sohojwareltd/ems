<?php

namespace App\Filament\Resources;

use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\ResourcePermissionTrait;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;


class CategoryResource extends Resource
{
    use ResourcePermissionTrait;
    protected static ?string $model = Category::class;
    protected static ?string $navigationLabel = 'Categories';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(function (string $state, callable $set) {
                    $set('slug', Str::slug($state));
                })
                ->helperText('Enter the category name as it will appear to customers.'),
            Forms\Components\TextInput::make('slug')
                ->required()
                ->maxLength(255)
                ->helperText('Unique URL slug for the category. Auto-generated from the name.'),
            Forms\Components\Textarea::make('description')
                ->helperText('Optional: Add a description for this category.'),

            Select::make('parent_id')
                ->label('Parent Category')
                ->options(function () {
                    return Category::whereNull('parent_id')
                        ->pluck('name', 'id')
                        ->toArray();
                })
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('parent.name')->label('Parent Category')->searchable(),
            Tables\Columns\TextColumn::make('slug')->searchable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])->filters([
            Filter::make('parent_id')
                ->label('Show Parent Category')
                ->query(fn ( $query) => $query->whereNull('parent_id'))
        ])
    
        ->actions([
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
