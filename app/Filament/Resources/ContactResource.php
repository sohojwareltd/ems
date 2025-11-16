<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
use App\Models\ContactCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnquiryReplyMail;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    
    protected static ?string $navigationLabel = 'Enquiries';
    
    protected static ?string $modelLabel = 'Enquiry';
    
    protected static ?string $pluralModelLabel = 'Enquiries';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                    ])->columns(2),
                
                Forms\Components\Section::make('Enquiry Details')
                    ->schema([
                        Forms\Components\Select::make('contact_category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->preload()
                            ->searchable(),
                        Forms\Components\Select::make('status')
                            ->options(Contact::getStatuses())
                            ->default(Contact::STATUS_NEW)
                            ->required(),
                        Forms\Components\Textarea::make('message')
                            ->rows(4)
                            ->columnSpanFull()
                            ->disabled(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Admin Response')
                    ->schema([
                        Forms\Components\Textarea::make('admin_reply')
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('replied_at')
                            ->disabled(),
                    ])
                    ->visible(fn ($record) => $record !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => Contact::getStatusColor($state))
                    ->formatStateUsing(fn (string $state): string => Contact::getStatuses()[$state] ?? $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable()
                    ->default('—'),
                Tables\Columns\TextColumn::make('message')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(Contact::getStatuses())
                    ->default(Contact::STATUS_NEW),
                Tables\Filters\SelectFilter::make('contact_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('reply')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->form([
                        Forms\Components\Textarea::make('reply_message')
                            ->label('Your Reply')
                            ->required()
                            ->rows(6),
                    ])
                    ->action(function (Contact $record, array $data): void {
                        // Send email
                        Mail::to($record->email)->send(new EnquiryReplyMail($record, $data['reply_message']));
                        
                        // Update record
                        $record->update([
                            'status' => Contact::STATUS_COMPLETED,
                            'admin_reply' => $data['reply_message'],
                            'replied_at' => now(),
                        ]);
                        
                        Notification::make()
                            ->title('Reply sent successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Contact $record): bool => $record->status !== Contact::STATUS_COMPLETED),
                Tables\Actions\ViewAction::make(),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'view' => Pages\ViewContact::route('/{record}'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
