<?php

namespace App\Filament\Resources\WeddingResource\Pages;

use App\Filament\Resources\WeddingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Umowa;

class EditWedding extends EditRecord
{
    protected static string $resource = WeddingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        // Pobieramy dane umowy z formularza
        $umowaData = $this->data['umowa'] ?? null;
        
        if ($umowaData && is_array($umowaData)) {
            // Sprawdzamy czy umowa już istnieje
            $umowa = Umowa::where('wedding_id', $this->record->id)->first();
            
            if ($umowa) {
                // Jeśli umowa istnieje, aktualizujemy ją
                $umowa->update($umowaData);
            } else {
                // Jeśli umowa nie istnieje, tworzymy nową
                $umowaData['wedding_id'] = $this->record->id;
                Umowa::create($umowaData);
            }
        }
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Pobieramy dane umowy dla tego wesela
        $umowa = $this->record->umowy()->first();
        
        if ($umowa) {
            // Dodajemy dane umowy do formularza
            $data['umowa'] = $umowa->toArray();
        }
        
        return $data;
    }
}
