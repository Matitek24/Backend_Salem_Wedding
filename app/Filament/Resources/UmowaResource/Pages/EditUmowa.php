<?php

namespace App\Filament\Resources\UmowaResource\Pages;

use App\Filament\Resources\UmowaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Wedding;

class EditUmowa extends EditRecord
{
    protected static string $resource = UmowaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        if ($this->record->status === 'podpisana') {
            $wedding = Wedding::find($this->record->wedding_id);
            if ($wedding) {
                $wedding->update(['typ_zamowienia' => 'umowa']);
            }
        }
        if ($this->record->status === 'anulowana' || $this->record->status === 'utworzona') {
            $wedding = Wedding::find($this->record->wedding_id);
            if ($wedding) {
                $wedding->update(['typ_zamowienia' => 'rezerwacja']);
            }
        }
    }
}
