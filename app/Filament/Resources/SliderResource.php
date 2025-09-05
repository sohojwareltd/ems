<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;
    
    protected static ?string $navigationGroup = 'Content Management';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Slider Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Main heading for the slider'),
                        
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->helperText('Subtitle or description text')
                            ->columnSpanFull(),
                        
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('sliders')
                
                            ->required()
                            ->helperText('Recommended size: 1200x400px')
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Button Settings')
                    ->schema([
                        Forms\Components\TextInput::make('button_text')
                            ->maxLength(255)
                            ->helperText('Text for the primary button (e.g., "Shop Now")'),
                        
                        Forms\Components\TextInput::make('button_url')
                            ->maxLength(255)
                            ->helperText('URL for the primary button (e.g., "/products")'),
                        
                        Forms\Components\ColorPicker::make('button_color')
                            ->default('#007bff')
                            ->helperText('Color for the button'),
                    ])->columns(3),
                
                Forms\Components\Section::make('Display Settings')
                    ->schema([
                        // Forms\Components\Select::make('position')
                        //     ->options([
                        //         'top' => 'Top',
                        //         'middle' => 'Middle', 
                        //         'bottom' => 'Bottom',
                        //     ])
                        //     ->default('top')
                        //     ->helperText('Position of the slider'),
                        
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active sliders will be displayed'),
                    ])->columns(3),
                
                Forms\Components\Section::make('Schedule')
                    ->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->helperText('When to start showing this slider (optional)'),
                        
                        Forms\Components\DateTimePicker::make('ends_at')
                            ->helperText('When to stop showing this slider (optional)'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->size(60)
                    ->square(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('description')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('button_text')
                    ->label('Button')
                    ->limit(20),
                
                Tables\Columns\TextColumn::make('position')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'top' => 'success',
                        'middle' => 'warning',
                        'bottom' => 'info',
                    }),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('position')
                    ->options([
                        'top' => 'Top',
                        'middle' => 'Middle',
                        'bottom' => 'Bottom',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
