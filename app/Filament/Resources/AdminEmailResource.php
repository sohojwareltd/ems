<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminEmailResource\Pages;
use App\Models\AdminEmail;
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

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Email Setup')
                ->schema([
                    Forms\Components\TextInput::make('to_emails')
                        ->label('To')
                        ->required()
                        ->maxLength(65535)
                        ->placeholder('first@example.com, second@example.com')
                        ->helperText('Use comma-separated email addresses, like Gmail recipient input.'),
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
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('to_emails')
                    ->label('Recipients')
                    ->limit(40)
                    ->wrap(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdminEmails::route('/'),
            'create' => Pages\CreateAdminEmail::route('/create'),
            'edit' => Pages\EditAdminEmail::route('/{record}/edit'),
        ];
    }

    public static function sendEmail(AdminEmail $record): void
    {
        try {
            app(AdminEmailSender::class)->send($record->fresh());

            Notification::make()
                ->title('Email sent successfully')
                ->success()
                ->send();
        } catch (\Throwable $throwable) {
            Notification::make()
                ->title('Email sending failed')
                ->body($throwable->getMessage())
                ->danger()
                ->send();
        }
    }
}
