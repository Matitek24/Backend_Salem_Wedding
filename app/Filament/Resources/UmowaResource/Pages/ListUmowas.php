<?php
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
                // Pobranie rekordu wesela na podstawie wedding_id
                $wedding = Wedding::find($data['wedding_id']);

                // Przekierowanie do strony tworzenia umowy wraz z dodatkowymi danymi
                $this->redirect(
                    UmowaResource::getUrl('create', [
                        'wedding_id' => $wedding->id,
                        'pakiet'    => $wedding->pakiet,
                        'data'      => $wedding->data,
                        'koscol'    => $wedding->koscol,
                        'sala'      => $wedding->sala,
                    ])
                );
            }),
        ];
    }
}