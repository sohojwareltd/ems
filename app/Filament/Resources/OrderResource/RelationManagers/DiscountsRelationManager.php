<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class DiscountsRelationManager extends RelationManager
{
    protected static string $relationship = 'discounts';

    protected static ?string $recordTitleAttribute = 'reason';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Select::make('type')
                    ->options([
                        'manual' => 'Manual Discount',
                        'coupon' => 'Coupon',
                        'loyalty' => 'Loyalty',
                        'promotion' => 'Promotion',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('reason')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reason')
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->money('usd')
                    ->color('danger')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'gray' => 'manual',
                        'primary' => 'coupon',
                        'success' => 'loyalty',
                        'warning' => 'promotion',
                    ]),
                Tables\Columns\TextColumn::make('reason')
                    ->limit(50),
                Tables\Columns\TextColumn::make('applied_by')
                    ->state(fn ($record) => $record->appliedBy->name)
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function () {
                        // Recalculate order total after adding discount
                        $this->getOwnerRecord()->recalculateTotal();
                        
                        Notification::make()
                            ->success()
                            ->title('Discount added successfully')
                            ->body('The discount has been added to the order.')
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->after(function () {
                        // Recalculate order total after removing discount
                        $this->getOwnerRecord()->recalculateTotal();
                        
                        Notification::make()
                            ->success()
                            ->title('Discount removed successfully')
                            ->body('The discount has been removed from the order.')
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(function () {
                            // Recalculate order total after bulk removing discounts
                            $this->getOwnerRecord()->recalculateTotal();
                            
                            Notification::make()
                                ->success()
                                ->title('Discounts removed successfully')
                                ->body('The selected discounts have been removed from the order.')
                                ->send();
                        }),
                ]),
            ]);
    }
}
