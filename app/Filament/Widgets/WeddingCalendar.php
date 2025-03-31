<?php

namespace App\Filament\Widgets;

use App\Models\Wedding;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WeddingCalendar extends FullCalendarWidget
{
    use InteractsWithForms;

    protected static ?string $heading = 'Kalendarz Wesel';

    // Właściwość dla aktualnie edytowanego rekordu
    public Model|string|int|null $record = null;

public function fetchEvents(array $fetchInfo): array
{
    $start = $fetchInfo['start'] ?? null;
    $end   = $fetchInfo['end'] ?? null;

    if ($start && $end) {
        return Wedding::query()
            ->where('data', '>=', $start)
            ->where('data', '<=', $end)
            ->get()
            ->map(function (Wedding $wedding) {
                $icons = '';
                if (Str::contains($wedding->pakiet, 'foto')) {
                    $icons .= '📷';
                }
                if (Str::contains($wedding->pakiet, 'film')) {
                    $icons .= '🎥';
                }
                if (Str::contains($wedding->pakiet, 'fotoplener')) {
                    $icons .= '🌴';
                }

                return [
                    'id'    => (string) $wedding->id,
                    'title' => $wedding->imie2 != null ? "{$icons}{$wedding->imie1} & {$wedding->imie2}" : "Wydarzenie <b>{$wedding->imie1}</b> ",
                    'start' => Carbon::parse($wedding->data)->format('Y-m-d'),
                    'allDay' => true,
                   'color' => match ($wedding->typ_zamowienia) {
                    'umowa' => '#27bd41',
                    'event' => '#F5412F',
                    'rezerwacja_terminu' => '#ff0000', // Jaskrawy czerwony
                    default => '#4881f6',
                },
                ];
            })
            ->toArray();
    }

    return [];
}


    public function getActions(): array
    {
        return [
            CreateAction::make()->modalHeading('Dodaj Wesele')
                ->form([
                    Forms\Components\DatePicker::make('data')
                        ->label('Data wesela')
                        ->required()
                        ->default(now()->toDateString()),
                    // Dodajemy nowe pola do szybkiego dodania
                    Forms\Components\TextInput::make('imie1')->label('Imię Panny Młodej')->required(),
                    Forms\Components\TextInput::make('imie2')->label('Imię Pana Młodego')->required(),
                    Forms\Components\TextInput::make('telefon_panny')->label('Telefon Panny Młodej')->required(),
                    Forms\Components\TextInput::make('telefon_pana')->label('Telefon Pana Młodego')->required(),
                    Forms\Components\Select::make('pakiet')
                        ->label('Pakiet')
                        ->options([
                            'film' => 'Film',
                            'foto' => 'Foto',
                            'fot+film' => 'Fot+Film',
                            'foto+film+fotoplener' => 'Foto+Film+Fotoplener',
                            'foto+fotoplener' => 'Foto+Fotoplener',
                        ])
                        ->required(),
                    Forms\Components\Textarea::make('uwagi')
                        ->label('Uwagi')
                        ->rows(3)
                        ->placeholder('Dodaj dodatkowe informacje')
                        ->maxLength(500),
                ])
                ->mutateFormDataUsing(fn (array $data): array => [
                    'data' => $data['data'],
                    'imie1' => $data['imie1'] ?? '',
                    'imie2' => $data['imie2'] ?? '',
                    'telefon_panny' => $data['telefon_panny'] ?? '',
                    'telefon_pana' => $data['telefon_pana'] ?? '',
                    'pakiet' => $data['pakiet'] ?? '',
                    'uwagi' => $data['uwagi'] ?? '',
                    'typ_zamowienia' => $data['typ_zamowienia'] ?? 'rezerwacja',
                ])
                ->action(fn (array $data) => Wedding::create($data)),
        ];
    }

    protected function createEvent(array $data): Model
    {
        return Wedding::create([
            'data' => $data['start'],
        ]);
    }

    protected function editEvent(Model $record, array $data): Model
    {
        $record->update([
            'data' => $data['start'],
        ]);
        return $record;
    }

    protected function updateEvent(Model $record, array $data): Model
    {
        $record->update([
            'data' => $data['start'],
        ]);
        return $record;
    }

    public function eventDidMount(): string
    {
        return <<<JS
        function({ event, el }) {
            // Szukamy elementu, który zawiera tytuł (np. fc-event-title)
            let titleEl = el.querySelector('.fc-event-title');
            if (titleEl) {
                // Ustawiamy innerHTML, aby zawartość tytułu interpretowała HTML
                titleEl.innerHTML = event.title;
            }
            
            // Pozostałe ustawienia, np. tooltip i obsługa kliknięcia
            el.setAttribute("x-tooltip", "tooltip");
            el.setAttribute("x-data", "{ tooltip: '"+event.title.replace(/"/g, '&quot;')+"' }");
            el.setAttribute("data-event-id", event.id);
            console.log("Event ID:", event.id);
            el.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log("Kliknięto event z ID:", event.id);
                if (event.id) {
                    window.location.href = "/admin/weddings/" + event.id + "/edit";
                }
            });
        }
    JS;
    }
    
    
    public function config(): array
    {
        return [
            'events' => $this->fetchEvents([]),
            'select' => true,
            'selectOverlap' => false,
            'eventDrop' => 'refreshCalendar',
            'eventResize' => 'refreshCalendar',
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }
    
    protected $listeners = ['editWedding'];

    public function editWedding($id)
    {
        $wedding = Wedding::find($id);
        if (!$wedding) {
            return;
        }
        $this->record = $wedding;
        $this->form->fill($wedding->toArray());
        $this->dispatchBrowserEvent('openEditModal');
    }
    
    public function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('imie1')->label('Imię Panny Młodej')->required(),
            Forms\Components\TextInput::make('imie2')->label('Imię Pana Młodego')->required(),
            Forms\Components\DatePicker::make('data')->label('Data Wesela')->required(),
            Forms\Components\Select::make('typ_wesela')
                ->label('Typ Wesela')
                ->options([
                    'boho' => 'Boho',
                    'klasyczny' => 'Klasyczny',
                    'plenerowy' => 'Plenerowy',
                ])
                ->default('boho'),
            Forms\Components\TextInput::make('sala')->label('Sala Weselna')->default(''),
            Forms\Components\TextInput::make('koscol')->label('Kościół')->default(''),
            Forms\Components\TextInput::make('liczba_gosci')->label('Liczba Gości')->numeric()->default(0),
            // Nowe pola w formularzu edycji
            Forms\Components\TextInput::make('telefon_panny')->label('Telefon Panny Młodej')->required(),
            Forms\Components\TextInput::make('telefon_pana')->label('Telefon Pana Młodego')->required(),
            Forms\Components\Select::make('pakiet')
                ->label('Pakiet')
                ->options([
                    'film' => 'Film',
                    'foto' => 'Foto',
                    'fot+film' => 'Fot+Film',
                    'foto+film+fotoplener' => 'Foto+Film+Fotoplener',
                    'foto+fotoplener' => 'Foto+Fotoplener',
                ])
                ->required(),
            Forms\Components\Textarea::make('uwagi')
                ->label('Uwagi')
                ->rows(3)
                ->placeholder('Dodaj dodatkowe informacje')
                ->maxLength(500),
        ];
    }
    
    public function saveEdit()
    {
        if ($this->record) {
            $this->record->update($this->form->getState());
            $this->dispatchBrowserEvent('closeEditModal');
        }
    }
}
