<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AudioBookResource\Pages;
use App\Models\AudioBook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class AudioBookResource extends Resource
{
    use ResourcePermissionTrait;
    protected static ?string $model = AudioBook::class;
    protected static ?string $navigationLabel = 'Audio Books';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Basic Information')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Title')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Enter the title of the audiobook.'),
                    Forms\Components\TextInput::make('author')
                        ->label('Author')
                        ->maxLength(255)
                        ->helperText('Who is the author of this audiobook?'),
                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->rows(3)
                        ->helperText('A short summary or description for this audiobook.'),
                    Forms\Components\FileUpload::make('cover_image')
                        ->label('Cover Image')
                        ->image()
                        ->directory('audio_book_covers')
                        ->maxSize(2048)
                        ->helperText('Upload a cover image for this audiobook (JPG, PNG, max 2MB).')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                ])->columns(2),

            Forms\Components\Section::make('Audio Files (Chapters/Tracks)')
                ->schema([
                    Forms\Components\Repeater::make('audio_files')
                        ->label('Audio Files')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Track Title')
                                ->required()
                                ->maxLength(255)
                                ->helperText('Name of the chapter or track.'),
                            Forms\Components\FileUpload::make('file')
                                ->directory('audio_book_files')
                                ->label('Audio File')
                                ->required()
                                ->helperText('Upload the audio file for this track.'),
                            Forms\Components\TextInput::make('duration')
                                ->numeric()
                                ->label('Duration (seconds)')
                                ->nullable()
                                ->helperText('Length of the track in seconds.'),
                            Forms\Components\Toggle::make('trial')
                                ->label('Trial (publicly playable)')
                                ->default(false)
                                ->helperText('Enable if this track should be available as a free trial.'),
                        ])
                        ->addActionLabel('Add Track')
                        ->minItems(1)
                        ->collapsible()
                        ->helperText('Add one or more audio files (chapters or tracks) for this audiobook.'),
                ]),

            Forms\Components\Section::make('Relations & Settings')
                ->schema([
                    Forms\Components\Select::make('products')
                        ->relationship('products', 'name')
                        ->multiple()
                        ->label('Related Products')
                        ->helperText('Link this audiobook to one or more products.'),
                    Forms\Components\TextInput::make('download_limit')
                        ->numeric()
                        ->nullable()
                        ->label('Download Limit (per file)')
                        ->helperText('Set a per-file download limit for this audiobook. Leave blank for unlimited.'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('author')->sortable(),
            Tables\Columns\ImageColumn::make('cover_image')->label('Cover'),
            Tables\Columns\TextColumn::make('duration')->label('Duration (s)'),
            Tables\Columns\TextColumn::make('created_at')->dateTime('M d, Y')->sortable(),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAudioBooks::route('/'),
            'create' => Pages\CreateAudioBook::route('/create'),
            'edit' => Pages\EditAudioBook::route('/{record}/edit'),
        ];
    }
} 