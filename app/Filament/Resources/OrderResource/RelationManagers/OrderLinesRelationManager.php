<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;

class OrderLinesRelationManager extends RelationManager
{
    protected static string $relationship = 'lines';
    protected static ?string $title = 'Order Lines';

    public  function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            // No form fields for now
        ]);
    }

    public  function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('product_name')->label('Product'),
            Tables\Columns\TextColumn::make('sku')->label('SKU'),
            Tables\Columns\TextColumn::make('price')->money('usd'),
            Tables\Columns\TextColumn::make('quantity'),
            Tables\Columns\TextColumn::make('total')->money('usd'),
            Tables\Columns\TextColumn::make('notes')->label('Notes'),
        ]);
    }
} 