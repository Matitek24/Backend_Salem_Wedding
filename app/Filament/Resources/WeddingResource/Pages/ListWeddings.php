<?php

namespace App\Filament\Resources\WeddingResource\Pages;

use App\Filament\Resources\WeddingResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use App\Filament\Widgets\WeddingCalendar; // Importujemy kalendarz
use App\Models\Wedding;

class ListWeddings extends ListRecords
{
    protected static string $resource = WeddingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->icon('heroicon-o-plus-circle')
            ->label('Dodaj Wesele') 
            ->color('success'), 
            Actions\Action::make('quickAddWedding')
                ->label('Dodaj Szybkie Wesele')
                ->icon('heroicon-o-plus-circle')
                ->modalHeading('Dodaj szybkie wesele')
                ->color("info")
                ->form([
                    \Filament\Forms\Components\TextInput::make('imie1')
                        ->label('Imię Panny Młodej')
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('imie2')
                        ->label('Imię Pana Młodego')
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('data')
                        ->label('Data Wesela')
                        ->required(),
                ])
                ->action(function (array $data) {
                    Wedding::create([
                        'imie1' => $data['imie1'],
                        'imie2' => $data['imie2'],
                        'data' => $data['data'],
                    ]);
                })
                ->successNotificationTitle('Wesele dodane!'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WeddingCalendar::class, // Dodajemy widget kalendarza
        ];
    }
}
