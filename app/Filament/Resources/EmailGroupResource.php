<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailGroupResource\Pages;
use App\Models\EmailGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmailGroupResource extends Resource
{
    protected static ?string $model = EmailGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Email Groups';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Email Group';

    protected static ?string $pluralModelLabel = 'Email Groups';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Select::make('parent_id')
                        ->label('Parent Group')
                        ->options(fn () => EmailGroup::whereNull('parent_id')->pluck('title', 'id'))
                        ->searchable()
                        ->nullable()
                        ->placeholder('None (this is a group)')
                        ->live(),

                    Forms\Components\TextInput::make('title')
                        ->label('Group Title')
                        ->required(fn (\Filament\Forms\Get $get) => ! $get('parent_id'))
                        ->visible(fn (\Filament\Forms\Get $get) => ! $get('parent_id'))
                        ->maxLength(255),

                    Forms\Components\TagsInput::make('email')
                        ->label('Custom Emails')
                        ->required(fn (Get $get) => (bool) $get('parent_id') && blank($get('email_file')))
                        ->helperText('Enter and add multiple emails. Each tag is one email.')
                        ->separator(',')
                        ->live()
                        ->formatStateUsing(function (?string $state): array {
                            if (blank($state)) {
                                return [];
                            }
                            // Parse comma-separated or semicolon-separated emails
                            $emails = preg_split('/[,;]/', $state) ?: [];
                            return array_map(fn ($e) => strtolower(trim($e)), $emails);
                        }),

                    Forms\Components\FileUpload::make('email_file')
                        ->label('Upload Excel File')
                        ->acceptedFileTypes([
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ])
                        ->required(fn (Get $get) => (bool) $get('parent_id') && blank($get('email')))
                        ->disk('local')
                        ->directory('email-imports')
                        ->helperText('All valid emails from the Excel file will be imported. Duplicates will be skipped.'),
                ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->getStateUsing(fn (EmailGroup $record) => $record->isGroup() ? 'Group' : 'Email')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Group' => 'primary',
                        'Email' => 'success',
                        default  => 'gray',
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label('Group Title')
                    ->searchable()
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->placeholder('—')
                    ->copyable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.title')
                    ->label('Parent Group')
                    ->placeholder('—')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('children_count')
                    ->label('Emails')
                    ->counts('children')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'group' => 'Groups Only',
                        'email' => 'Emails Only',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value'] === 'group') {
                            $query->whereNull('parent_id');
                        } elseif ($data['value'] === 'email') {
                            $query->whereNotNull('parent_id');
                        }
                    }),

                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Group')
                    ->options(fn () => EmailGroup::whereNull('parent_id')->pluck('title', 'id'))
                    ->placeholder('All Groups'),
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
            ->defaultSort('parent_id')
            ->reorderable(false);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEmailGroups::route('/'),
            'create' => Pages\CreateEmailGroup::route('/create'),
            'edit'   => Pages\EditEmailGroup::route('/{record}/edit'),
        ];
    }
}
