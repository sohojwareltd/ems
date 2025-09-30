<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaperCodeResource\Pages;
use App\Filament\Resources\PaperCodeResource\RelationManagers;
use App\Models\PaperCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PaperCodeResource extends Resource
{
    protected static ?string $model = PaperCode::class;

    protected static ?string $navigationLabel = 'Paper Codes';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function (string $state, callable $set) {
                    $set('slug', Str::slug($state));
                })
                ->maxLength(255),

            Forms\Components\TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('slug')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->date('d-m-Y'),
        ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPaperCodes::route('/'),
            'create' => Pages\CreatePaperCode::route('/create'),
            'edit' => Pages\EditPaperCode::route('/{record}/edit'),
        ];
    }
}
