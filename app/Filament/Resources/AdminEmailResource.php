<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminEmailResource\Pages;
use App\Models\AdminEmail;
use App\Models\EmailGroup;
use App\Services\AdminEmailSender;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AdminEmailResource extends Resource
{
    protected static ?string $model = AdminEmail::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Custom Emails';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Custom Email';

    protected static ?string $pluralModelLabel = 'Custom Emails';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static function getEmailGroupOptions(): array
    {
        return EmailGroup::query()
            ->whereNull('parent_id')
            ->withCount('children')
            ->orderBy('title')
            ->get()
            ->mapWithKeys(fn (EmailGroup $group): array => [
                $group->id => sprintf('%s (%d child emails)', $group->title, $group->children_count),
            ])
            ->all();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Email Setup')
                ->schema([
                    Forms\Components\Select::make('email_groups')
                        ->label('Email Groups')
                        ->options(fn (): array => static::getEmailGroupOptions())
                        ->searchable()
                        ->preload()
                        ->multiple()
                        ->helperText('Each group shows its total child email count.')
                        ->live(),
                    Forms\Components\TagsInput::make('to_emails')
                        ->label('Additional Emails')
                        ->placeholder('first@example.com, second@example.com')
                        ->helperText('Optional: Add comma-separated email addresses in addition to selected groups.')
                        ->separator(',')
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

                            return collect(preg_split('/[\s,;]+/', $state) ?: [])
                                ->map(fn (string $email): string => strtolower(trim($email)))
                                ->filter()
                                ->values()
                                ->all();
                        })
                        ->dehydrateStateUsing(fn (?array $state): ?string => blank($state) ? null : implode(', ', $state)),
                    Forms\Components\TagsInput::make('cc_emails')
                        ->label('CC Emails')
                        ->placeholder('cc1@example.com, cc2@example.com')
                        ->helperText('Optional: Add comma-separated CC recipients.')
                        ->separator(',')
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

                            return collect(preg_split('/[\s,;]+/', $state) ?: [])
                                ->map(fn (string $email): string => strtolower(trim($email)))
                                ->filter()
                                ->values()
                                ->all();
                        })
                        ->dehydrateStateUsing(fn (?array $state): ?string => blank($state) ? null : implode(', ', $state)),
                    Forms\Components\TagsInput::make('bcc_emails')
                        ->label('BCC Emails')
                        ->placeholder('bcc1@example.com, bcc2@example.com')
                        ->helperText('Optional: Add comma-separated BCC recipients.')
                        ->separator(',')
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

                            return collect(preg_split('/[\s,;]+/', $state) ?: [])
                                ->map(fn (string $email): string => strtolower(trim($email)))
                                ->filter()
                                ->values()
                                ->all();
                        })
                        ->dehydrateStateUsing(fn (?array $state): ?string => blank($state) ? null : implode(', ', $state)),
                    Forms\Components\FileUpload::make('attachments')
                        ->label('Attachments')
                        ->disk('local')
                        ->directory('admin-email-attachments')
                        ->multiple()
                        ->storeFileNamesIn('attachment_file_names')
                        ->downloadable()
                        ->openable()
                        ->helperText('Optional: Upload one or more files to send with this email.')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('subject')
                        ->required()
                        ->maxLength(255),
                ])
                ->columns(2),
            Forms\Components\Section::make('Email Content')
                ->schema([
                    Forms\Components\RichEditor::make('body')
                        ->required()
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'strike',
                            'h2',
                            'h3',
                            'bulletList',
                            'orderedList',
                            'blockquote',
                            'link',
                            'redo',
                            'undo',
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('group_or_emails')
                    ->label('Group/Emails')
                    ->state(fn (AdminEmail $record): string => static::getGroupOrEmailsLabel($record))
                    ->wrap(),

                Tables\Columns\TextColumn::make('emails_count')
                    ->label('Number of Emails')
                    ->state(fn (AdminEmail $record): int => count($record->to_recipients))
                    ->badge(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('reply')
                    ->label('Reply')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('gray')
                    ->url(fn (AdminEmail $record): string => static::getUrl('reply', ['replyTo' => $record])),
                Tables\Actions\Action::make('send')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (AdminEmail $record) => static::sendEmail($record)),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected static function getGroupOrEmailsLabel(AdminEmail $record): string
    {
        $groupIds = $record->email_groups;

        if (is_string($groupIds)) {
            $decoded = json_decode($groupIds, true);
            $groupIds = is_array($decoded) ? $decoded : [];
        }

        $groupTitles = EmailGroup::query()
            ->whereNull('parent_id')
            ->whereIn('id', (array) $groupIds)
            ->orderBy('title')
            ->pluck('title')
            ->filter()
            ->values()
            ->all();

        if (! empty($groupTitles)) {
            return implode(', ', $groupTitles);
        }

        $customEmails = AdminEmail::parseRecipients($record->to_emails);

        if (empty($customEmails)) {
            return '—';
        }

        $preview = array_slice($customEmails, 0, 2);
        $remaining = count($customEmails) - count($preview);

        return $remaining > 0
            ? implode(', ', $preview) . " +{$remaining}"
            : implode(', ', $preview);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdminEmails::route('/'),
            'create' => Pages\CreateAdminEmail::route('/create'),
            'reply' => Pages\ReplyAdminEmail::route('/{replyTo}/reply'),
            'edit' => Pages\EditAdminEmail::route('/{record}/edit'),
        ];
    }

    public static function sendEmail(AdminEmail $record): void
    {
        try {
            app(AdminEmailSender::class)->send($record->fresh());

            Notification::make()
                ->title('Email queued successfully')
                ->success()
                ->send();
        } catch (\Throwable $throwable) {
            Notification::make()
                ->title('Email queueing failed')
                ->body($throwable->getMessage())
                ->danger()
                ->send();
        }
    }
}
