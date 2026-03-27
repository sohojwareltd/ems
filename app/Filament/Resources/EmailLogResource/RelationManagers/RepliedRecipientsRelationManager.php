<?php

namespace App\Filament\Resources\EmailLogResource\RelationManagers;

use App\Models\AdminEmail;
use App\Models\EmailRecipient;
use App\Services\AdminEmailSender;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RepliedRecipientsRelationManager extends RelationManager
{
    protected static string $relationship = 'recipients';

    protected static ?string $title = 'Replied Emails';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereNotNull('replied_at')->latest('replied_at'))
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'to' => 'info',
                        'cc' => 'warning',
                        'bcc' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('replied_at')
                    ->label('Replied At')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reply_payload.Subject')
                    ->label('Reply Subject')
                    ->limit(70)
                    ->toggleable(),
            ])
            ->actions([
                Tables\Actions\Action::make('viewReply')
                    ->label('View Reply')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Reply Details')
                    ->form([
                        Forms\Components\TextInput::make('from')
                            ->label('From')
                            ->disabled(),

                        Forms\Components\TextInput::make('subject')
                            ->label('Subject')
                            ->disabled(),

                        Forms\Components\Textarea::make('main_reply')
                            ->label('Main Reply')
                            ->rows(8)
                            ->disabled(),
                    ])
                    ->fillForm(fn (EmailRecipient $record): array => [
                        'from' => $this->payloadValue($record, 'From') ?? 'N/A',
                        'subject' => $this->payloadValue($record, 'Subject') ?? 'N/A',
                        'main_reply' => $this->extractMainReply($this->rawReplyText($record)) ?? 'No message text found',
                    ])
                    ->action(static function (): void {
                    })
                    ->modalSubmitActionLabel('Close'),

                Tables\Actions\Action::make('sendReply')
                    ->label('Reply Again')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->modalHeading('Send Reply')
                    ->form([
                        Forms\Components\TextInput::make('subject')
                            ->label('Subject')
                            ->required()
                            ->maxLength(255)
                            ->default(fn (EmailRecipient $record): string => $this->defaultReplySubject($record)),

                        Forms\Components\RichEditor::make('body')
                            ->label('Reply Message')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data, EmailRecipient $record): void {
                        try {
                            $adminEmail = AdminEmail::create([
                                'to_emails' => $record->email,
                                'cc_emails' => null,
                                'bcc_emails' => null,
                                'email_groups' => null,
                                'subject' => $data['subject'],
                                'body' => $data['body'],
                                'created_by' => Auth::id(),
                            ]);

                            app(AdminEmailSender::class)->send($adminEmail);

                            Notification::make()
                                ->title('Reply sent successfully')
                                ->success()
                                ->send();
                        } catch (\Throwable $exception) {
                            Notification::make()
                                ->title('Failed to send reply')
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->headerActions([])
            ->paginated([10, 25, 50]);
    }

    private function defaultReplySubject(EmailRecipient $record): string
    {
        $subject = $this->payloadValue($record, 'Subject')
            ?? $record->emailLog?->subject
            ?? 'Reply';

        return Str::startsWith(strtolower($subject), 're:') ? $subject : 'Re: ' . $subject;
    }

    private function payloadValue(EmailRecipient $recipient, string $key): ?string
    {
        $value = data_get($recipient->reply_payload, $key);

        return is_string($value) && $value !== '' ? $value : null;
    }

    private function rawReplyText(EmailRecipient $recipient): ?string
    {
        $textBody = $this->payloadValue($recipient, 'TextBody');
        if ($textBody !== null) {
            return $this->normalizeText($textBody);
        }

        $htmlBody = $this->payloadValue($recipient, 'HtmlBody');
        if ($htmlBody !== null) {
            return $this->normalizeText(strip_tags($htmlBody));
        }

        return null;
    }

    private function extractMainReply(?string $text): ?string
    {
        if ($text === null || $text === '') {
            return null;
        }

        // Handle Gmail-style quoted headers that can span multiple lines before "wrote:".
        $parts = preg_split('/\nOn .*?wrote:\s*/is', $text, 2);
        $main = trim($parts[0] ?? '');

        // Remove any inline quoted lines if they exist in the top block.
        $main = preg_replace('/^>.*$/m', '', $main) ?? $main;
        $main = trim($main);

        return $main !== '' ? $main : null;
    }

    private function normalizeText(string $text): string
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", $text);
        $normalized = preg_replace('/[ \t]+/', ' ', $normalized) ?? $normalized;
        $normalized = preg_replace('/\n{3,}/', "\n\n", $normalized) ?? $normalized;

        return trim($normalized);
    }
}
