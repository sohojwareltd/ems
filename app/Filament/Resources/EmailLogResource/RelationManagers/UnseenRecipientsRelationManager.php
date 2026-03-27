<?php

namespace App\Filament\Resources\EmailLogResource\RelationManagers;

use App\Models\AdminEmail;
use App\Models\EmailReplyMessage;
use App\Mail\AdminCustomEmail;
use App\Models\EmailRecipient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UnseenRecipientsRelationManager extends RelationManager
{
    protected static string $relationship = 'recipients';

    protected static ?string $title = 'Unseen Emails (Grouped by Type)';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->whereNull('replied_at')
                ->orderBy('type')
                ->orderBy('email'))
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Group')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'to' => 'info',
                        'cc' => 'warning',
                        'bcc' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('email')
                    ->label('Unseen Email')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Group')
                    ->options([
                        'to' => 'To',
                        'cc' => 'Cc',
                        'bcc' => 'Bcc',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('sendReply')
                    ->label('Send New Reply')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->modalHeading('Send New Reply')
                    ->form([
                        Forms\Components\TextInput::make('subject')
                            ->label('Subject')
                            ->required()
                            ->maxLength(255)
                            ->default(fn (EmailRecipient $record): string => $this->defaultReplySubject($record)),

                        Forms\Components\RichEditor::make('body')
                            ->label('Message')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data, EmailRecipient $record): void {
                        try {
                            $adminEmail = new AdminEmail([
                                'to_emails' => $record->email,
                                'cc_emails' => null,
                                'bcc_emails' => null,
                                'email_groups' => null,
                                'subject' => $data['subject'],
                                'body' => $data['body'],
                                'created_by' => Auth::id(),
                            ]);

                            Mail::to($record->email)->send(new AdminCustomEmail($adminEmail));

                            EmailReplyMessage::query()->create([
                                'email_log_id' => $record->email_log_id,
                                'email_recipient_id' => $record->id,
                                'direction' => 'outbound',
                                'from_email' => (string) config('mail.from.address'),
                                'subject' => $data['subject'],
                                'text_body' => trim(strip_tags((string) $data['body'])),
                                'html_body' => $data['body'],
                                'payload' => null,
                                'received_at' => now(),
                            ]);

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
        $subject = $record->emailLog?->subject ?? 'New Reply';

        return Str::startsWith(strtolower($subject), 're:') ? $subject : 'Re: ' . $subject;
    }
}
