<?php

// app/Filament/Resources/UmowaResource/Pages/ListUmowy.php
// app/Filament/Resources/UmowaResource/Pages/ListUmowy.php
namespace App\Filament\Resources\UmowaResource\Pages;

use App\Filament\Resources\UmowaResource;
use App\Models\Wedding;
use Filament\Forms;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\URL;

class ListUmowas extends ListRecords
{
    protected static string $resource = UmowaResource::class;

  // app/Filament/Resources/UmowaResource/Pages/ListUmowy.php
protected function getActions(): array
{
    return [
        Actions\Action::make('createUmowa')
        ->label('Utwórz umowę')
        ->icon('heroicon-o-plus')
        ->color('primary')
        ->form([
            Forms\Components\Select::make('wedding_id')
                ->label('Wybierz parę młodą')
                ->options(function () {
                    return Wedding::where('typ_zamowienia', 'rezerwacja')
                        ->get()
                        ->mapWithKeys(function ($wedding) {
                            return [
                                $wedding->id => "{$wedding->imie1} & {$wedding->imie2} - {$wedding->data}"
                            ];
                        });
                })
                ->required()
                ->searchable(),
        ])
        ->action(function (array $data): void {
            // Przekazujemy wedding_id do strony create jako parametr query
            $this->redirect(UmowaResource::getUrl('create', ['wedding_id' => $data['wedding_id']]));
        }),
    ];
}
}