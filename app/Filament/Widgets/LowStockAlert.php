<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LowStockAlert extends BaseWidget
{
    protected static ?string $heading = 'Low Stock Alert';
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        try {
            return Auth::user()?->can('dashboard.products') ?? false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                ->where('is_digital', false)
                    ->where(function (Builder $query) {
                        $query->where(function (Builder $subQuery) {
                            // Regular products with low stock
                            $subQuery->where('has_variants', false)
                                    ->where('stock', '<=', 10)
                                    ->where('track_quantity', true);
                        })->orWhere(function (Builder $subQuery) {
                            // Products with variants that have low stock
                            $subQuery->where('has_variants', true)
                                    ->whereJsonLength('variants', '>', 0)
                                    ->whereRaw("JSON_EXTRACT(variants, '$[*].stock') IS NOT NULL")
                                    ->whereRaw("EXISTS (
                                        SELECT 1 FROM JSON_TABLE(
                                            variants,
                                            '$[*]' COLUMNS (stock INT PATH '$.stock')
                                        ) AS jt WHERE jt.stock <= 10
                                    )");
                        });
                    })
                    ->orderBy('stock', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('stock')
                    ->label('Current Stock')
                    ->formatStateUsing(function ($record) {
                        if ($record->hasVariants()) {
                            $minStock = collect($record->variants)
                                ->pluck('stock')
                                ->filter()
                                ->min();
                            return $minStock ?? 0;
                        }
                        return $record->stock;
                    })
                    ->sortable()
                    ->color(function ($record) {
                        $stock = $record->hasVariants() 
                            ? collect($record->variants)->pluck('stock')->filter()->min() ?? 0
                            : $record->stock;
                        return $stock <= 5 ? 'danger' : 'warning';
                    })
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(function ($record) {
                        if ($record->hasVariants()) {
                            $minPrice = $record->getMinPrice();
                            $maxPrice = $record->getMaxPrice();
                            if ($minPrice == $maxPrice) {
                                return '$' . number_format($minPrice, 2);
                            }
                            return '$' . number_format($minPrice, 2) . ' - $' . number_format($maxPrice, 2);
                        }
                        return '$' . number_format($record->price, 2);
                    })
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('has_variants')
                    ->label('Type')
                    ->boolean()
                    ->trueIcon('heroicon-o-squares-2x2')
                    ->falseIcon('heroicon-o-cube')
                    ->trueColor('info')
                    ->falseColor('gray')
                    ->getStateUsing(fn ($record) => $record->hasVariants()),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn ($record) => $record->status === 'active'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Update Stock')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Product $record): string => route('filament.admin.resources.products.edit', $record))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('stock', 'asc');
    }
} 