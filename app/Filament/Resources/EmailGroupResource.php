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
use Illuminate\Database\Eloquent\Builder;

class EmailGroupResource extends Resource
{
    protected static ?string $model = EmailGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Email Groups';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Email Group';

    protected static ?string $pluralModelLabel = 'Email Groups';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereNull('parent_id');
    }

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
                        ->formatStateUsing(function (array | string | null $state): array {
                            if (blank($state)) {
                                return [];
                            }

                            if (is_array($state)) {
                                return collect($state)
                                    ->map(fn (string $email): string => strtolower(trim($email)))
                                    ->filter()
                                    ->values()
                                    ->all();
                            }

                            return collect(preg_split('/[,;]/', $state) ?: [])
                                ->map(fn (string $email): string => strtolower(trim($email)))
                                ->filter()
                                ->values()
                                ->all();
                        })
                        ->dehydrateStateUsing(fn (?array $state): ?string => blank($state) ? null : implode(',', $state)),

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
                // Tables\Columns\TextColumn::make('group_type')
                //     ->label('Parent')
                //     ->state('Parent')
                //     ->badge()
                //     ->color('primary'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Group Title')
                    ->searchable()
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('children_count')
                    ->label('Emails')
                    ->counts('children')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_emails')
                    ->label('Has Emails')
                    ->query(fn (Builder $query): Builder => $query->has('children')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'view'   => Pages\ViewEmailGroup::route('/{record}'),
            'edit'   => Pages\EditEmailGroup::route('/{record}/edit'),
        ];
    }
}
