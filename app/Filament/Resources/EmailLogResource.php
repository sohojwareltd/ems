<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailLogResource\Pages;
use App\Filament\Resources\EmailLogResource\RelationManagers\RepliedRecipientsRelationManager;
use App\Filament\Resources\EmailLogResource\RelationManagers\UnseenRecipientsRelationManager;
use App\Models\EmailLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components as InfoComponents;

class EmailLogResource extends Resource
{
    protected static ?string $model = EmailLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationLabel = 'Email Reports';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Email Log';

    protected static ?string $pluralModelLabel = 'Email Logs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Email Details')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->disabled(),
                        Forms\Components\TextInput::make('from_email')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'queued' => 'Queued',
                                'sent' => 'Sent',
                                'failed' => 'Failed',
                                'partial' => 'Partially Sent',
                            ])
                            ->disabled(),
                        Forms\Components\TextInput::make('total_recipients')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Reply Status')
                    ->schema([
                        Forms\Components\TextInput::make('replied_count')
                            ->label('Replied Count')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('pending_count')
                            ->label('Pending Count')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Email Body')
                    ->schema([
                        Forms\Components\RichEditor::make('body')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->whereNull('parent_id'))
            ->columns([
                TextColumn::make('subject')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                // TextColumn::make('from_email')
                //     ->label('From')
                //     ->searchable(),

                TextColumn::make('total_recipients')
                    ->label('Total')
                    ->numeric()
                    ->sortable()
                    ->badge(),

                TextColumn::make('replied_count')
                    ->label('Replied')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('pending_count')
                    ->label('Pending')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'queued' => 'info',
                        'failed' => 'danger',
                        'partial' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('sent_at')
                    ->dateTime()
                    ->sortable(),

                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->label('Created at'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'queued' => 'Queued',
                        'sent' => 'Sent',
                        'failed' => 'Failed',
                        'partial' => 'Partially Sent',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoComponents\Section::make('Email Overview')
                    ->schema([
                        InfoComponents\TextEntry::make('subject')
                            ->label('Subject'),

                        InfoComponents\TextEntry::make('from_email')
                            ->label('From Email'),

                        InfoComponents\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'sent' => 'success',
                                'queued' => 'info',
                                'failed' => 'danger',
                                'partial' => 'warning',
                                default => 'gray',
                            }),

                        InfoComponents\TextEntry::make('sent_at')
                            ->dateTime()
                            ->label('Sent At'),
                    ])
                    ->columns(2),

                InfoComponents\Section::make('Reply Statistics')
                    ->schema([
                        InfoComponents\TextEntry::make('total_recipients')
                            ->label('Total Recipients')
                            ->badge()
                            ->color('info'),

                        InfoComponents\TextEntry::make('replied_count')
                            ->label('Replied')
                            ->badge()
                            ->color('success'),

                        InfoComponents\TextEntry::make('pending_count')
                            ->label('Pending')
                            ->badge()
                            ->color('warning'),

                        InfoComponents\TextEntry::make('reply_percentage')
                            ->label('Reply Rate')
                            ->getStateUsing(function (EmailLog $record): string {
                                $percentage = $record->getReplyStatusSummary()['replied_percentage'];
                                return "{$percentage}%";
                            })
                            ->badge()
                            ->color('info'),

                        InfoComponents\TextEntry::make('total_reply_messages')
                            ->label('Total Messages')
                            ->getStateUsing(fn (EmailLog $record): int => $record->replyMessages()->count())
                            ->badge()
                            ->color('gray'),

                        InfoComponents\TextEntry::make('inbound_replies')
                            ->label('Inbound Replies')
                            ->getStateUsing(fn (EmailLog $record): int => $record->replyMessages()->where('direction', 'inbound')->count())
                            ->badge()
                            ->color('success'),

                        InfoComponents\TextEntry::make('outbound_replies')
                            ->label('Sent Replies')
                            ->getStateUsing(fn (EmailLog $record): int => $record->replyMessages()->where('direction', 'outbound')->count())
                            ->badge()
                            ->color('primary'),
                    ])
                    ->columns(4),

                InfoComponents\Section::make('Email Content')
                    ->schema([
                        InfoComponents\TextEntry::make('body')
                            ->label('Body')
                            ->html()
                            ->columnSpanFull(),
                    ]),

                InfoComponents\Section::make('Timestamps')
                    ->schema([
                        InfoComponents\TextEntry::make('created_at')
                            ->label('Created at')
                            ->dateTime(),

                        InfoComponents\TextEntry::make('updated_at')
                            ->label('Updated at')
                            ->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RepliedRecipientsRelationManager::class,
            UnseenRecipientsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailLogs::route('/'),
            'view' => Pages\ViewEmailLog::route('/{record}'),
        ];
    }
}
