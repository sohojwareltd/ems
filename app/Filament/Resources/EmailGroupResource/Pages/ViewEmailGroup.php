<?php

namespace App\Filament\Resources\EmailGroupResource\Pages;

use App\Filament\Resources\EmailGroupResource;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewEmailGroup extends ViewRecord
{
    protected static string $resource = EmailGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Group Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->label('Group Title')
                            ->icon('heroicon-o-user-group'),

                        Infolists\Components\TextEntry::make('children_count')
                            ->label('Total Emails')
                            ->state(fn($record) => $record->children()->count())
                            ->badge()
                            ->color('primary'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Email List')
                    ->schema([
                        Infolists\Components\TextEntry::make('email_list')
                            ->hiddenLabel()
                            ->html()
                            ->state(function ($record) {
                                $emails = $record->children()
                                    ->orderBy('email')
                                    ->pluck('email');

                                return '<ul class="list-disc pl-5 space-y-1">'
                                    . $emails->map(
                                        fn($email) =>
                                        "<li class='flex items-center gap-2'>
                                        <svg class='w-4 h-4 text-gray-400' fill='none' stroke='currentColor'
                                            viewBox='0 0 24 24'>
                                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                                                d='M16 12H8m8 4H8m8-8H8'></path>
                                        </svg>
                                        {$email}
                                    </li>"
                                    )->implode('')
                                    . '</ul>';
                            }),
                    ]),
            ]);
    }
}
