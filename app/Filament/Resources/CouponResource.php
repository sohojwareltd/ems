<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ResourcePermissionTrait;

class CouponResource extends Resource
{
    use ResourcePermissionTrait;

    protected static ?string $model = Coupon::class;
    protected static ?string $navigationLabel = 'Coupons';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('code')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255)
                ->helperText('Coupon code customers will enter.'),
            Forms\Components\Select::make('type')
                ->options([
                    'percent' => 'Percent',
                    'fixed' => 'Fixed Amount',
                ])->required()->helperText('Type of discount.'),
            Forms\Components\TextInput::make('value')->numeric()->required()->helperText('Discount value (percent or fixed amount).'),
            Forms\Components\TextInput::make('max_uses')->numeric()->nullable()->helperText('Maximum number of times this coupon can be used.'),
            Forms\Components\TextInput::make('min_order')->numeric()->nullable()->helperText('Minimum order amount to use this coupon.'),
            Forms\Components\DateTimePicker::make('starts_at')->label('Starts At')->nullable()->helperText('Coupon start date.'),
            Forms\Components\DateTimePicker::make('ends_at')->label('Ends At')->nullable()->helperText('Coupon end date.'),
            Forms\Components\Toggle::make('is_active')->label('Active')->default(true)->helperText('Is this coupon currently active?'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('code')->searchable(),
            Tables\Columns\BadgeColumn::make('type')->colors([
                'primary' => 'percent',
                'success' => 'fixed',
            ]),
            Tables\Columns\TextColumn::make('value'),
            Tables\Columns\TextColumn::make('used'),
            Tables\Columns\TextColumn::make('max_uses'),
            Tables\Columns\TextColumn::make('min_order'),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
            Tables\Columns\TextColumn::make('starts_at')->dateTime(),
            Tables\Columns\TextColumn::make('ends_at')->dateTime(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])->actions([
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
