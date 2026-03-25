<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static function generateUniqueCouponCode(?int $ignorePlanId = null): string
    {
        do {
            $code = Str::upper(Str::random(8));

            $exists = Plan::query()
                ->where('coupon_code', $code)
                ->where('is_coupon_enabled', true)
                ->where('active', true)
                ->when($ignorePlanId, fn ($query) => $query->whereKeyNot($ignorePlanId))
                ->exists();
        } while ($exists);

        return $code;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Plan Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        RichEditor::make('description')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('currency')
                            ->default('usd')
                            ->required()
                            ->maxLength(3),
                        Forms\Components\Select::make('interval')
                            ->options([
                                'day' => 'Daily',
                                'week' => 'Weekly',
                                'month' => 'Monthly',
                                'year' => 'Yearly',
                            ])
                            ->default('month')
                            ->required(),
                        Forms\Components\TextInput::make('interval_count')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->minValue(1),
                        Forms\Components\TextInput::make('trial_period_days')
                            ->numeric()
                            ->nullable()
                            ->minValue(0),
                        Forms\Components\Toggle::make('active')
                            ->required(),
                        Forms\Components\Toggle::make('is_hide')
                            ->label('Hide')
                            ->default(false)
                            ->required(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Subscription Access Code')
                    ->schema([
                        Forms\Components\Toggle::make('is_coupon_enabled')
                            ->label(' Enable access code')
                            ->afterStateUpdated(function (Set $set, Get $get, ?bool $state): void {
                                if (! $state || filled($get('coupon_code'))) {
                                    return;
                                }

                                $set('coupon_code', static::generateUniqueCouponCode());
                            })
                            ->live(),
                        Forms\Components\TextInput::make('coupon_code')
                            ->label('Access code')
                            ->default(fn (): string => static::generateUniqueCouponCode())
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => (bool) $get('is_coupon_enabled'))
                            ->dehydrated(fn (Get $get): bool => (bool) $get('is_coupon_enabled'))
                            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Str::upper(trim($state)) : null)
                            ->unique(
                                ignoreRecord: true,
                                modifyRuleUsing: fn (Unique $rule) => $rule
                                    ->where('is_coupon_enabled', true)
                                    ->where('active', true),
                            )
                            ->helperText('Users can activate this plan without bank details using this code.')
                            ->visible(fn (Get $get): bool => (bool) $get('is_coupon_enabled')),
                        Forms\Components\TextInput::make('coupon_max_uses')
                            ->label('Maximum uses')
                            ->numeric()
                            ->minValue(1)
                            ->nullable()
                            ->visible(fn (Get $get): bool => (bool) $get('is_coupon_enabled')),
                        Forms\Components\TextInput::make('coupon_total_used')
                            ->label('Total used')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->default(0)
                            ->visible(fn (Get $get): bool => (bool) $get('is_coupon_enabled')),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('interval')
                    ->searchable(),
                Tables\Columns\TextColumn::make('interval_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_coupon_enabled')
                    ->label('Coupon')
                    ->boolean(),
                Tables\Columns\TextColumn::make('coupon_code')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('coupon_total_used')
                    ->label('Used')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('coupon_max_uses')
                    ->label('Max Uses')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('trial_period_days')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_hide')
                    ->label('Hide')
                    ->boolean(),
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
