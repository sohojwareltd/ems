<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PastPaperResource\Pages;
use App\Filament\Resources\PastPaperResource\RelationManagers;
use App\Models\PastPaper;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PastPaperResource extends Resource
{
    protected static ?string $model = PastPaper::class;

    protected static ?string $navigationLabel = 'Past Papers';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 7;


    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Past Paper Form')
                ->tabs([

                    // ✅ Content Tab
                    Tab::make('General')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $state, callable $set) {
                                    $set('slug', Str::slug($state));
                                }),

                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\Select::make('year')
                                ->required()
                                ->options([
                                    '2018' => '2018',
                                    '2019' => '2019',
                                    '2020' => '2020',
                                    '2021' => '2021',
                                    '2022' => '2022',
                                    '2023' => '2023',
                                    '2024' => '2024',
                                ]),

                            Forms\Components\Select::make('month')
                                ->required()
                                ->options([
                                    'January' => 'January',
                                    'June' => 'June',
                                    'November' => 'November',
                                ]),
                            Forms\Components\Select::make('paper_code_id')
                                ->relationship('paperCode', 'name')
                                ->label('Paper Code')
                                ->required(),
                            // Forms\Components\Select::make('topic_id')
                            //     ->relationship('topic', 'name')
                            //     ->label('Topic')
                            //     ->required()
                            //     ->searchable(),

                            Forms\Components\Select::make('qualiification_id')
                                ->relationship('qualiification', 'title')
                                ->label('Qualification')
                                ->required(),

                            Forms\Components\Select::make('subject_id')
                                ->relationship('subject', 'title')
                                ->label('Subject')
                                ->required(),

                            Forms\Components\Select::make('examboard_id')
                                ->relationship('examboard', 'title')
                                ->label('Exam Board')
                                ->required(),
                            Forms\Components\Select::make('paper_id')
                                ->relationship('paper', 'name')
                                ->label('Paper')
                                ->required(),
                            // Forms\Components\Select::make('resource_type_id')
                            //     ->label('Resource')
                            //     ->relationship('resource', 'title')
                            //     // ->searchable()
                            //     ->required()
                            //     ->helperText('Assign a resource for better organization.'),
                        ])
                        ->columns(2),

                    // ✅ Media Tab
                    Tab::make('Media')
                        ->schema([
                            Forms\Components\FileUpload::make('file')
                                ->label('Question Paper')
                                ->directory('pastpapers/pdf'),
                            Forms\Components\FileUpload::make('mark')
                                ->label('Mark Scheme')
                                ->directory('pastpapers/mark'),
                            Forms\Components\FileUpload::make('power_point')
                                ->label(' Examiner’s Report')
                                ->directory('pastpapers/ppt'),
                        ])
                        ->columns(2),
                ])
                ->columnSpanFull()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('paperCode.name')
                    ->label('Paper Code')
                    ->searchable(),
                TextColumn::make('year')->label('Year')->sortable(),
                TextColumn::make('month')->label('Month')->sortable(),
                // TextColumn::make('topic.name')->label('Topic'),
                TextColumn::make('created_at')->date('d M Y'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListPastPapers::route('/'),
            'create' => Pages\CreatePastPaper::route('/create'),
            'edit' => Pages\EditPastPaper::route('/{record}/edit'),
        ];
    }
}
