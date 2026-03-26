<?php

namespace App\Filament\Resources\AdminEmailResource\Pages;

use App\Filament\Resources\AdminEmailResource;
use App\Models\AdminEmail;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;

class ReplyAdminEmail extends CreateRecord
{
    protected static string $resource = AdminEmailResource::class;

    protected static bool $canCreateAnother = false;

    #[Locked]
    public ?int $replyToId = null;

    public function mount(): void
    {
        parent::mount();

        $this->replyToId = (int) request()->route('replyTo');
        $original        = AdminEmail::query()->findOrFail($this->replyToId);

        $subject = (string) ($original->subject ?? '');
        if (! str_starts_with(strtolower($subject), 're:')) {
            $subject = 'Re: ' . $subject;
        }

        $this->form->fill([
            'email_groups'          => null,
            'to_emails'             => $original->to_recipients,
            'cc_emails'             => $original->cc_emails,
            'bcc_emails'            => $original->bcc_emails,
            'attachments'           => (array) ($original->attachments ?? []),
            'attachment_file_names' => (array) ($original->attachment_file_names ?? []),
            'subject'               => $subject,
            'body'                  => '',
        ]);
    }

    public function form(Form $form): Form
    {
        $original       = AdminEmail::query()->find($this->replyToId);
        $hasAttachments = ! empty($original?->attachments);

        return $form->schema([
            Forms\Components\Section::make('Reply To')
                ->schema([
                    Forms\Components\TagsInput::make('to_emails')
                        ->label('To')
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
                        ->dehydrateStateUsing(fn (?array $state): ?string => blank($state) ? null : implode(', ', $state))
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('subject')
                        ->label('Subject')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Hidden::make('cc_emails'),
                    Forms\Components\Hidden::make('bcc_emails'),
                    Forms\Components\Hidden::make('email_groups'),
                ])
                ->columns(1),

            Forms\Components\Section::make('Attachments')
                ->schema([
                    Forms\Components\FileUpload::make('attachments')
                        ->label('Attachments')
                        ->disk('local')
                        ->directory('admin-email-attachments')
                        ->multiple()
                        ->storeFileNamesIn('attachment_file_names')
                        ->downloadable()
                        ->openable(),
                ])
                ->visible($hasAttachments)
                ->columns(1),

            Forms\Components\Section::make('Reply Content')
                ->schema([
                    Forms\Components\RichEditor::make('body')
                        ->label('Your Reply')
                        ->required()
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'bold', 'italic', 'underline', 'strike',
                            'h2', 'h3', 'bulletList', 'orderedList',
                            'blockquote', 'link', 'redo', 'undo',
                        ]),
                ]),
        ]);
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Send Reply');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();

        return $data;
    }

    protected function afterCreate(): void
    {
        AdminEmailResource::sendEmail($this->record);
    }
}
