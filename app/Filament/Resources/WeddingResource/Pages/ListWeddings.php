<?php

namespace App\Filament\Resources\WeddingResource\Pages;

use App\Filament\Resources\WeddingResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use App\Filament\Widgets\WeddingCalendar; 
use App\Models\Wedding;

class ListWeddings extends ListRecords
{
    protected static string $resource = WeddingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('quickAddWedding')
                ->label('Rezerwacja Terminu')
                ->icon('heroicon-o-plus-circle')
                ->modalHeading('Rezerwacja Terminu')
                ->color("info")
                ->form([
                    \Filament\Forms\Components\TextInput::make('imie1')
                        ->label('Imię Panny Młodej')
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('imie2')
                        ->label('Imię Pana Młodego'),
                    \Filament\Forms\Components\TextInput::make('telefon_panny')
                        ->label('Telefon Panny Młodej')
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('telefon_pana')
                        ->label('Telefon Pana Młodego')
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('data')
                        ->label('Data Wesela')
                        ->required(),
                    \Filament\Forms\Components\Select::make('pakiet')
                        ->label('Pakiet')
                        ->options([
                            'film' => 'Film',
                            'foto' => 'Foto',
                            'fot+film' => 'Fot+Film',
                            'foto+film+fotoplener' => 'Foto+Film+Fotoplener',
                            'foto+fotoplener' => 'Foto+Fotoplener',
                        ]),
                    \Filament\Forms\Components\Textarea::make('uwagi')
                        ->label('Uwagi')
                        ->rows(3)
                        ->placeholder('Dodaj dodatkowe informacje')
                        ->maxLength(500),
                ])
                ->action(function (array $data) {
                    Wedding::create([
                        'imie1'            => $data['imie1'],
                        'imie2'            => $data['imie2'],
                        'data'             => $data['data'],
                        'telefon_panny'    => $data['telefon_panny'],
                        'telefon_pana'     => $data['telefon_pana'],
                        'pakiet'           => $data['pakiet'],
                        'uwagi'            => $data['uwagi'],
                        'typ_zamowienia'   => 'rezerwacja',
                    ]);
                })
                ->successNotificationTitle('Wesele dodane!'),
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->label('Dodaj Wesele (Informacje)') 
                ->color('success'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WeddingCalendar::class,
        ];
    }
}
