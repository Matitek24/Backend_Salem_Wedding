<?php

namespace App\Filament\Resources\WeddingResource\Pages;

use App\Filament\Resources\WeddingResource;
use App\Models\Umowa;
use Filament\Resources\Pages\CreateRecord;

class CreateWedding extends CreateRecord
{
    protected static string $resource = WeddingResource::class;
    
    // Nadpisujemy metodę do obsługi tworzenia rekordu
    protected function afterCreate(): void
    {
        // Pobieramy dane umowy z formularza
        $umowaData = $this->data['umowa'] ?? null;
        
        if ($umowaData && is_array($umowaData)) {
            // Uzupełniamy dane wspólne z rekordu wesela, aby nie wprowadzać ich dwa razy
            $umowaData['data'] = $this->record->data;
            $umowaData['pakiet'] = $this->record->pakiet;
            $umowaData['sala'] = $this->record->sala;
            $umowaData['koscol'] = $this->record->koscol;
            
            // Tworzymy nową umowę powiązaną z nowo utworzonym weselem
            $umowaData['wedding_id'] = $this->record->id;
            
            // Usuwamy id, jeśli istnieje (na wszelki wypadek)
            unset($umowaData['id']);
            
            // Tworzymy nowy rekord umowy
            Umowa::create($umowaData);
        }
    }
}
