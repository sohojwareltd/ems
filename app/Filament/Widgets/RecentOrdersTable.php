<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RecentOrdersTable extends BaseWidget
{
    protected static ?string $heading = 'Recent Orders';
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        try {
            return Auth::user()?->can('dashboard.orders') ?? false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order #')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->user->email ?? 'Guest')
                    ->icon('heroicon-o-user'),
                
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('usd')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'pending',
                        'blue' => 'confirmed',
                        'yellow' => 'processing',
                        'purple' => 'shipped',
                        'green' => 'delivered',
                        'orange' => 'returned',
                        'red' => 'refunded',
                        'secondary' => 'cancelled',
                        'success' => 'completed',
                    ])
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'warning' => 'refunded',
                    ])
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->icon('heroicon-o-calendar'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Order $record): string => route('filament.admin.resources.orders.edit', $record))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('created_at', 'desc');
    }
} 