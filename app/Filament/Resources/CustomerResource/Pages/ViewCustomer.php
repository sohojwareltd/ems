<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\KeyValue;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Customer Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Full Name')
                                    ->weight('bold')
                                    ->size(TextEntry\TextEntrySize::Large),
                                TextEntry::make('email')
                                    ->label('Email Address')
                                    ->copyable()
                                    ->icon('heroicon-o-envelope'),
                                TextEntry::make('phone')
                                    ->label('Phone Number')
                                    ->icon('heroicon-o-phone'),
                                TextEntry::make('role.display_name')
                                    ->label('Account Type')
                                    ->badge()
                                    ->color('primary'),
                                TextEntry::make('email_verified_at')
                                    ->label('Email Verified')
                                    ->dateTime()
                                    ->icon('heroicon-o-check-circle')
                                    ->color('success'),
                                TextEntry::make('created_at')
                                    ->label('Member Since')
                                    ->dateTime()
                                    ->icon('heroicon-o-calendar'),
                            ]),
                    ]),

                Section::make('Contact Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('address')
                                    ->label('Address')
                                    ->icon('heroicon-o-map-pin'),
                                TextEntry::make('city')
                                    ->label('City')
                                    ->icon('heroicon-o-building-office'),
                                TextEntry::make('state')
                                    ->label('State/Province')
                                    ->icon('heroicon-o-map'),
                                TextEntry::make('zip')
                                    ->label('ZIP/Postal Code')
                                    ->icon('heroicon-o-identification'),
                                TextEntry::make('country')
                                    ->label('Country')
                                    ->icon('heroicon-o-flag'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Account Statistics')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('orders_count')
                                    ->label('Total Orders')
                                    ->getStateUsing(function ($record) {
                                        return $record->orders()->count();
                                    })
                                    ->icon('heroicon-o-shopping-bag')
                                    ->color('primary'),
                                TextEntry::make('total_spent')
                                    ->label('Total Spent')
                                    ->getStateUsing(function ($record) {
                                        return $record->orders()->sum('total');
                                    })
                                    ->money('usd')
                                    ->icon('heroicon-o-currency-dollar')
                                    ->color('success'),
                                TextEntry::make('last_order_date')
                                    ->label('Last Order')
                                    ->getStateUsing(function ($record) {
                                        $lastOrder = $record->orders()->latest()->first();
                                        if (!$lastOrder) {
                                            return 'No orders yet';
                                        }
                                        return $lastOrder->created_at;
                                    })
                                    ->icon('heroicon-o-clock')
                                    ->color('warning')
                                    ->formatStateUsing(function ($state) {
                                        if ($state === 'No orders yet') {
                                            return $state;
                                        }
                                        return $state->format('M j, Y g:i A');
                                    }),
                            ]),
                    ]),
            ]);
    }
} 