<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EssayResource\Pages;
use App\Models\Essay;
use App\Models\Topic;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class EssayResource extends Resource
{
    protected static ?string $model = Essay::class;
    protected static ?string $navigationLabel = 'Model Essays';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 7;

    public static function getNavigationBadge(): ?string
    {
        return (string) Essay::query()->count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Product Tabs')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('General')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $state, callable $set) {
                                    $baseSlug = Str::slug($state);
                                    $slug     = $baseSlug;
                                    $counter  = 1;
                                    while (Essay::where('slug', $slug)->exists()) {
                                        $slug = $baseSlug . '-' . Str::random(4);
                                        $counter++;
                                    }

                                    $set('slug', $slug);
                                })
                                ->helperText('Enter the product name as it will appear to customers.'),

                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                ->helperText('Unique URL slug for the product. Auto-generated from the name.'),

                            Forms\Components\Select::make('qualiification_id')
                                ->label('Qualification')
                                ->relationship('qualiification', 'title')
                                ->required()
                                ->helperText('Assign a qualification for better organization.'),

                            Forms\Components\Select::make('subject_id')
                                ->label('Subject')
                                ->relationship('subject', 'title')
                                ->required()
                                ->helperText('Assign a subject for better organization.'),

                            Forms\Components\Select::make('examboard_id')
                                ->label('Examboard')
                                ->relationship('examboard', 'title')
                                ->required()
                                ->helperText('Assign an examboard for better organization.'),

                            Forms\Components\TextInput::make('year')
                                ->required()
                                ->label('Year'),

                            Forms\Components\Select::make('month')
                                ->required()
                                ->options([
                                    'January' => 'January',
                                    'June' => 'June',
                                    'November' => 'November',
                                ])
                                ->label('Month'),

                            Forms\Components\Select::make('marks')
                                ->required()
                                ->options([
                                    '6' => '6 Marks',
                                    '9' => '9 Marks',
                                    '12' => '12 Marks',
                                ])
                                ->label('Marks'),

                            Forms\Components\Select::make('paper_id')
                                ->label('Paper')
                                ->relationship('paper', 'name')
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn(callable $set) => [
                                    $set('paper_code_id', null),
                                    $set('topics', []),
                                ])
                                ->helperText('Associate the essay with a specific paper.'),

                            Forms\Components\Select::make('paper_code_id')
                                ->label('Paper Code')
                                ->required()
                                ->reactive()
                                ->options(function (callable $get) {
                                    $paperId = $get('paper_id');
                                    if (!$paperId) {
                                        return [];
                                    }

                                    return \App\Models\PaperCode::where('paper_id', $paperId)
                                        ->pluck('name', 'id');
                                })
                                ->disabled(fn(callable $get) => !$get('paper_id'))
                                ->helperText('Select the code corresponding to the chosen paper.'),

                            Forms\Components\Select::make('topics')
                                ->label('Topics')
                                ->multiple()
                                ->reactive()
                                ->dehydrated(false)
                                ->options(function (callable $get) {
                                    $paperId = $get('paper_id');


                                    if (!$paperId) {
                                        return [];
                                    }

                                    return \App\Models\Topic::where('paper_id', $paperId)
                                        ->pluck('name', 'id');
                                })
                                ->afterStateHydrated(function ($component, $state, $record) {
                                    if ($record) {
                                        $component->state(
                                            $record->topics()->pluck('topics.id')->toArray() // ✅ fully qualified
                                        );
                                    }
                                })
                                ->disabled(fn(callable $get) => !$get('paper_id'))
                                ->helperText('Select the topics related to the chosen paper code.'),


                            Forms\Components\Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'active' => 'Active',
                                    'archived' => 'Archived',
                                ])
                                ->default('draft')
                                ->required()
                                ->helperText('Set the product status.'),

                            Forms\Components\Toggle::make('is_sample')
                                ->label('Sample Essay')
                                ->helperText('Mark this as a sample essay (visible in Sample tab)')
                                ->default(false),
                        ]),

                    Forms\Components\Tabs\Tab::make('Media')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Forms\Components\FileUpload::make('file')
                                ->label('PDF File')
                                ->directory('products/essay')
                                ->acceptedFileTypes(['application/pdf'])
                                ->required()
                                ->maxSize(5120),

                            Forms\Components\FileUpload::make('ppt_file')
                                ->label('Zip File')
                                ->directory('products/powerpoints'),
                        ]),
                ])
                ->maxWidth('full')
                ->columns(2)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('paper.name')->label('Paper')->searchable(),
                TextColumn::make('paperCode.name')->label('Paper Code')->searchable(),
                TextColumn::make('year')->sortable(),
                TextColumn::make('month')->sortable(),
                TextColumn::make('marks')->sortable(),
                TextColumn::make('topics.name')
                    ->label('Topics')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy(
                            Topic::select('name')
                                ->join('essay_topic', 'topics.id', '=', 'essay_topic.topic_id')
                                ->whereColumn('essay_topic.essay_id', 'essays.id')
                                ->limit(1),
                            $direction
                        );
                    })
                    ->limit(50),
                TextColumn::make('created_at')->date('d M Y'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEssays::route('/'),
            'create' => Pages\CreateEssay::route('/create'),
            'edit' => Pages\EditEssay::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['topics']); // prevent saving topics directly
        return $data;
    }
}
