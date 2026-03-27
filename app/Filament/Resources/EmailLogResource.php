<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailLogResource\Pages;
use App\Models\EmailLog;
use App\Models\EmailRecipient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components as InfoComponents;
use Illuminate\Support\Str;

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
            ->columns([
                TextColumn::make('subject')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('from_email')
                    ->label('From')
                    ->searchable(),

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

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created at'),
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
                    ])
                    ->columns(4),

                InfoComponents\Section::make('Replied Emails')
                    ->schema([
                        InfoComponents\RepeatableEntry::make('replied_recipients')
                            ->state(fn (EmailLog $record): array => self::getRepliedRecipientsState($record))
                            ->hiddenLabel()
                            ->schema([
                                InfoComponents\TextEntry::make('email')
                                    ->label('Email')
                                    ->copyable(),

                                InfoComponents\TextEntry::make('type')
                                    ->label('Type')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'to' => 'info',
                                        'cc' => 'warning',
                                        'bcc' => 'danger',
                                        default => 'gray',
                                    }),

                                InfoComponents\TextEntry::make('replied_at')
                                    ->label('Replied At')
                                    ->dateTime()
                                    ->placeholder('Not replied'),

                                InfoComponents\TextEntry::make('reply_from')
                                    ->label('Reply From')
                                    ->placeholder('N/A'),

                                InfoComponents\TextEntry::make('reply_subject')
                                    ->label('Reply Subject')
                                    ->placeholder('N/A')
                                    ->limit(70),

                                InfoComponents\TextEntry::make('reply_preview')
                                    ->label('Reply Preview')
                                    ->placeholder('No reply body captured')
                                    ->wrap()
                                    ->columnSpan(2),

                                InfoComponents\TextEntry::make('status')
                                    ->label('Status')
                                    ->state('Replied')
                                    ->badge()
                                    ->color('success'),
                            ])
                            ->columns(2)
                            ->contained(false),
                    ])
                    ->collapsible(),

                InfoComponents\Section::make('Unseen / Pending Emails')
                    ->schema([
                        InfoComponents\RepeatableEntry::make('pending_recipients')
                            ->state(fn (EmailLog $record): array => self::getPendingRecipientsState($record))
                            ->hiddenLabel()
                            ->schema([
                                InfoComponents\TextEntry::make('email')
                                    ->label('Email')
                                    ->copyable(),

                                InfoComponents\TextEntry::make('type')
                                    ->label('Type')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'to' => 'info',
                                        'cc' => 'warning',
                                        'bcc' => 'danger',
                                        default => 'gray',
                                    }),

                                InfoComponents\TextEntry::make('status')
                                    ->label('Status')
                                    ->state('Unseen')
                                    ->badge()
                                    ->color('warning'),
                            ])
                            ->columns(3)
                            ->contained(false),
                    ])
                    ->collapsible(),

                InfoComponents\Section::make('Reply Conversation')
                    ->description('Shows each captured inbound reply, similar to Gmail top-reply plus quoted thread.')
                    ->schema([
                        InfoComponents\RepeatableEntry::make('reply_threads')
                            ->state(fn (EmailLog $record): array => self::getReplyThreadsState($record))
                            ->hiddenLabel()
                            ->schema([
                                InfoComponents\TextEntry::make('reply_from')
                                    ->label('From')
                                    ->placeholder('N/A')
                                    ->columnSpan(1),

                                InfoComponents\TextEntry::make('reply_subject')
                                    ->label('Subject')
                                    ->placeholder('N/A')
                                    ->columnSpan(1),

                                InfoComponents\TextEntry::make('replied_at')
                                    ->label('Replied At')
                                    ->dateTime()
                                    ->placeholder('N/A')
                                    ->columnSpan(1),

                                InfoComponents\TextEntry::make('main_reply')
                                    ->label('Main Reply')
                                    ->placeholder('No main reply text found')
                                    ->wrap()
                                    ->columnSpan(2),

                                InfoComponents\TextEntry::make('quoted_thread')
                                    ->label('Quoted Thread')
                                    ->placeholder('No quoted thread detected')
                                    ->wrap()
                                    ->columnSpan(1),
                            ])
                            ->columns(3)
                            ->contained(false),
                    ])
                    ->collapsed(),

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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailLogs::route('/'),
            'view' => Pages\ViewEmailLog::route('/{record}'),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function getRepliedRecipientsState(EmailLog $record): array
    {
        return $record->recipients
            ->whereNotNull('replied_at')
            ->sortByDesc('replied_at')
            ->map(fn (EmailRecipient $recipient): array => [
                'email' => $recipient->email,
                'type' => $recipient->type,
                'replied_at' => $recipient->replied_at,
                'reply_from' => self::payloadValue($recipient, 'From'),
                'reply_subject' => self::payloadValue($recipient, 'Subject'),
                'reply_preview' => self::buildPreviewText($recipient),
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function getPendingRecipientsState(EmailLog $record): array
    {
        return $record->recipients
            ->whereNull('replied_at')
            ->map(fn (EmailRecipient $recipient): array => [
                'email' => $recipient->email,
                'type' => $recipient->type,
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function getReplyThreadsState(EmailLog $record): array
    {
        return $record->recipients
            ->whereNotNull('replied_at')
            ->sortByDesc('replied_at')
            ->map(function (EmailRecipient $recipient): array {
                $fullText = self::rawReplyText($recipient);

                return [
                    'reply_from' => self::payloadValue($recipient, 'From'),
                    'reply_subject' => self::payloadValue($recipient, 'Subject'),
                    'replied_at' => $recipient->replied_at,
                    'main_reply' => self::extractMainReply($fullText),
                    'quoted_thread' => self::extractQuotedThread($fullText),
                ];
            })
            ->values()
            ->all();
    }

    private static function payloadValue(EmailRecipient $recipient, string $key): ?string
    {
        $value = data_get($recipient->reply_payload, $key);

        return is_string($value) && $value !== '' ? $value : null;
    }

    private static function rawReplyText(EmailRecipient $recipient): ?string
    {
        $textBody = self::payloadValue($recipient, 'TextBody');
        if ($textBody !== null) {
            return self::normalizeText($textBody);
        }

        $htmlBody = self::payloadValue($recipient, 'HtmlBody');
        if ($htmlBody !== null) {
            return self::normalizeText(strip_tags($htmlBody));
        }

        return null;
    }

    private static function buildPreviewText(EmailRecipient $recipient): ?string
    {
        return self::extractMainReply(self::rawReplyText($recipient));
    }

    private static function extractMainReply(?string $text): ?string
    {
        if ($text === null || $text === '') {
            return null;
        }

        $parts = preg_split('/\nOn .+ wrote:\n/i', $text, 2);
        $main = trim($parts[0] ?? '');

        if ($main === '') {
            return null;
        }

        return Str::limit($main, 600);
    }

    private static function extractQuotedThread(?string $text): ?string
    {
        if ($text === null || $text === '') {
            return null;
        }

        if (preg_match('/\n(On .+ wrote:\n.+)$/is', $text, $matches) !== 1) {
            return null;
        }

        $quoted = trim($matches[1]);

        return $quoted !== '' ? Str::limit($quoted, 900) : null;
    }

    private static function normalizeText(string $text): string
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", $text);
        $normalized = preg_replace('/[ \t]+/', ' ', $normalized) ?? $normalized;
        $normalized = preg_replace('/\n{3,}/', "\n\n", $normalized) ?? $normalized;

        return trim($normalized);
    }
}
