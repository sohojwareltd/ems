<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopicResource\Pages;
use App\Filament\Resources\TopicResource\RelationManagers;
use App\Models\Topic;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TopicResource extends Resource
{


    protected static ?string $model = Topic::class;
    protected static ?string $navigationLabel = 'Topic';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),
            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true),
            Forms\Components\Select::make('paper_id')
                ->label('Paper')
                ->relationship('paper', 'name')
                ->required()
                ->helperText('Assign a paper for better organization.'),
            Forms\Components\Select::make('subject_id')
                ->label('Subject')
                ->relationship('subject', 'title') // Uses the 'subject' relation and shows 'title'
                ->required()
                ->helperText('Assign a subject for better organization.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                // TextColumn::make('slug')->searchable()->sortable(),
                     Tables\Columns\TextColumn::make('subject.title')
                    ->label('Subject')
                    ->sortable()
                    ->searchable(),
                     Tables\Columns\TextColumn::make('paper.name')
                    ->label('Paper')
                    ->sortable()
                    ->searchable()
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
            'index' => Pages\ListTopics::route('/'),
            'create' => Pages\CreateTopic::route('/create'),
            'edit' => Pages\EditTopic::route('/{record}/edit'),
        ];
    }
}
