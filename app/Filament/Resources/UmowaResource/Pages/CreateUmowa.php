<?php
// app/Filament/Resources/UmowaResource/Pages/CreateUmowa.php
namespace App\Filament\Resources\UmowaResource\Pages;

use App\Filament\Resources\UmowaResource;
use App\Models\Wedding;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Placeholder;

class CreateUmowa extends CreateRecord
{
    protected static string $resource = UmowaResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getFormSchema(): array
{
    // Pobierz ID wesela z parametru URL
    $weddingId = request()->query('wedding_id');
    $wedding = null;
    
    if ($weddingId) {
        $wedding = Wedding::find($weddingId);
    }
    
    // Pobierz standardowy schemat formularza z zasobu
    $schema = parent::getFormSchema();
    
    if ($wedding) {
        // Usuń ewentualne pole wedding_id, które mogłoby być częścią schematu
        foreach ($schema as $key => $field) {
            if ($field->getName() === 'wedding_id') {
                unset($schema[$key]);
                break;
            }
        }
        
        // Dodaj ukryte pole z wedding_id
        $schema = array_merge([
           Hidden::make('wedding_id')
                ->default($weddingId),
        ], $schema);
        
        // Opcjonalnie: dodaj informację o wybranej parze młodej na górze formularza
        array_unshift($schema, 
            Placeholder::make('selected_wedding')
                ->label('Wybrana para młoda')
                ->content("{$wedding->imie1} & {$wedding->imie2} - {$wedding->data}")
        );
        
        // Wstępne wypełnienie danych z wesela
        $this->fillFormWithWeddingData($schema, $wedding);
    }
    
    return $schema;
}

    
    protected function fillFormWithWeddingData(array &$schema, Wedding $wedding): void
    {
        foreach ($schema as &$field) {
            if (method_exists($field, 'getChildComponents')) {
                $children = $field->getChildComponents();
                if (!empty($children)) {
                    $this->fillFormWithWeddingData($children, $wedding);
                }
            }
            
            if (method_exists($field, 'getName')) {
                $name = $field->getName();
                
                switch ($name) {
                    case 'telefon_mlodego':
                        if (method_exists($field, 'default')) {
                            $field->default($wedding->telefon_pana);
                        }
                        break;
                    case 'telefon_mlodej':
                        if (method_exists($field, 'default')) {
                            $field->default($wedding->telefon_panny);
                        }
                        break;
                    case 'sala':
                            if (method_exists($field, 'default')) {
                                $field->default($wedding->sala);
                            }
                            break;
                    case 'koscol':
                                if (method_exists($field, 'default')) {
                                    $field->default($wedding->koscol);
                                }
                                break;
                }
            }
        }
    }

    protected function afterCreate(): void
    {
        // Jeśli status umowy to "podpisana"
        if ($this->record->status === 'podpisana') {
            $wedding = Wedding::find($this->record->wedding_id);
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
    
}