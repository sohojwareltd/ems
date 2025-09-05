<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Filament\Resources\BlogPostResource\RelationManagers;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use App\Models\BlogCategory;
use App\Models\User;

class BlogPostResource extends Resource
{
    use ResourcePermissionTrait;

    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationLabel = 'Posts';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->options(BlogCategory::pluck('name', 'id'))
                    ->required(),
                Select::make('user_id')
                    ->label('Author')
                    ->relationship('user', 'name')
                    ->options(User::pluck('name', 'id'))
                    ->required(),
                Textarea::make('excerpt')
                    ->maxLength(500),
                RichEditor::make('content')
                    ->required(),
                FileUpload::make('image')
                    ->image()
                    ->directory('blog-posts')
                    ->maxSize(2048),
                Toggle::make('status')
                    ->label('Published')
                    ->onColor('success')
                    ->offColor('secondary')
              
                    ->default(fn ($record) => $record?->status === 'published')
                    ->dehydrateStateUsing(fn ($state) => $state ? 'published' : 'draft')
                    ->afterStateHydrated(function ($component, $state) {
                        $component->state($state === 'published');
                    }),
                DateTimePicker::make('published_at')
                    ->label('Published At'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('Image')->circular(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Category')->sortable(),
                Tables\Columns\TextColumn::make('author.name')->label('Author')->sortable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->label('Published')
                    ->getStateUsing(fn($record) => $record->status === 'published')
                    ->afterStateUpdated(function ($state, $record) {
                        $record->status = $state ? 'published' : 'draft';
                        $record->save();
                    }),
                Tables\Columns\TextColumn::make('published_at')->dateTime('M d, Y')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('M d, Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('published')->label('Published'),
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
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
