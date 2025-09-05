<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;
use App\Filament\Resources\ResourcePermissionTrait;
use App\Models\Permission;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermissionResource extends Resource
{
    use ResourcePermissionTrait;

    protected static ?string $model = Permission::class;

    protected static ?string $navigationLabel = 'Permissions';
    protected static ?string $navigationGroup = 'People';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Permission Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Unique permission name (e.g., users.create, orders.view)'),
                        Forms\Components\TextInput::make('display_name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Human-readable name (e.g., Create Users, View Orders)'),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->helperText('Optional description of what this permission allows'),
                        Forms\Components\Select::make('group')
                            ->options([
                                'admin' => 'Admin',
                                'dashboard' => 'Dashboard',
                                'users' => 'Users',
                                'orders' => 'Orders',
                                'products' => 'Products',
                                'categories' => 'Categories',
                                'brands' => 'Brands',
                                'coupons' => 'Coupons',
                                'shipping' => 'Shipping',
                                'general' => 'General',
                            ])
                            ->required()
                            ->default('general')
                            ->helperText('Group to organize permissions'),
                    ])->columns(2),
                Forms\Components\Section::make('Assigned Roles')
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->label('Roles with this Permission')
                            ->multiple()
                            ->relationship('roles', 'display_name')
                            ->searchable()
                            ->preload()
                            ->helperText('Select which roles should have this permission'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('display_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('group')
                    ->colors([
                        'primary' => 'admin',
                        'success' => 'dashboard',
                        'warning' => 'users',
                        'info' => 'orders',
                        'danger' => 'products',
                        'secondary' => 'general',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles_count')
                    ->label('Roles')
                    ->counts('roles')
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.display_name')
                    ->label('Assigned Roles')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->searchable()
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
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'admin' => 'Admin',
                        'dashboard' => 'Dashboard',
                        'users' => 'Users',
                        'orders' => 'Orders',
                        'products' => 'Products',
                        'categories' => 'Categories',
                        'brands' => 'Brands',
                        'coupons' => 'Coupons',
                        'shipping' => 'Shipping',
                        'general' => 'General',
                    ]),
                Tables\Filters\Filter::make('has_roles')
                    ->label('Has Assigned Roles')
                    ->query(fn (Builder $query): Builder => $query->whereHas('roles')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
