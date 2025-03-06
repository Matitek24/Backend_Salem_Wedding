<?php

namespace App\Filament\Widgets;

use App\Models\Wedding;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Database\Eloquent\Model;

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
                ->map(fn (Wedding $wedding) => [
                    'id'    => (string) $wedding->id,
                    'title' => "{$wedding->imie1} & {$wedding->imie2} - {$wedding->sala}",
                    'start' => Carbon::parse($wedding->data)->format('Y-m-d'),
                    'allDay' => true,
                    'color' => empty($wedding->sala) || empty($wedding->typ_wesela) || empty($wedding->koscol) || empty($wedding->liczba_gosci)
                        ? '#4881f6'
                        : '#27bd41',
                ])
                ->toArray();
        }

        return [];
    }

    public function getActions(): array
    {
        // Pozostawiamy tylko CreateAction – edycja będzie odbywać się przez modal uruchamiany po kliknięciu wydarzenia
        return [
            CreateAction::make()->modalHeading('Dodaj Wesele')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('date')
                        ->label('Data wesela')
                        ->required()
                        ->default(now()->toDateString()),
                ])
                ->mutateFormDataUsing(fn (array $data): array => [
                    'date' => $data['date'],
                ])
                ->action(fn (array $data) => Wedding::create([
                    'date' => $data['date'],
                ])),
        ];
    }

    protected function createEvent(array $data): Model
    {
        return Wedding::create([
            'date' => $data['start'],
        ]);
    }

    protected function editEvent(Model $record, array $data): Model
    {
        $record->update([
            'date' => $data['start'],
        ]);
        return $record;
    }

    protected function updateEvent(Model $record, array $data): Model
    {
        $record->update([
            'date' => $data['start'],
        ]);
        return $record;
    }

    public function eventDidMount(): string
    {
        return <<<JS
            function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }) {
                // Dodajemy tooltip, jak to było wcześniej
                el.setAttribute("x-tooltip", "tooltip");
                el.setAttribute("x-data", "{ tooltip: '"+event.title+"' }");
    
                // Przypisujemy ID do elementu
                el.setAttribute("data-event-id", event.id);
    
                // Logowanie ID, aby sprawdzić, czy jest poprawnie przypisane
                console.log("Event ID:", event.id);
                
                // Kliknięcie na event
                el.addEventListener('click', function(e) {
                    e.preventDefault();  // Zatrzymujemy domyślną akcję
                    e.stopPropagation(); // Zatrzymujemy propagację, żeby nic nie robiło się poza tym
                    console.log("Kliknięto event z ID:", event.id);
                    
                    // Zmiana lokalizacji na stronę edycji, jeśli ID jest dostępne
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
    
    // Rejestrujemy listener Livewire
    protected $listeners = ['editWedding'];

    // Metoda wywoływana po kliknięciu w wydarzenie
    public function editWedding($id)
    {
        $wedding = Wedding::find($id);
        if (!$wedding) {
            return;
        }
        $this->record = $wedding;
        // Wypełniamy formularz danymi rekordu
        $this->form->fill($wedding->toArray());
        // Wysyłamy zdarzenie do przeglądarki, które otworzy modal (obsłuż to w JS/Alpine)
        $this->dispatchBrowserEvent('openEditModal');
    }
    
    // Definiujemy schemat formularza edycji – możesz go dowolnie modyfikować
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
        ];
    }
    
    // Metoda zapisu zmian z formularza edycji
    public function saveEdit()
    {
        if ($this->record) {
            $this->record->update($this->form->getState());
            // Po zapisaniu wysyłamy zdarzenie do zamknięcia modalu – obsłuż to po stronie JS
            $this->dispatchBrowserEvent('closeEditModal');
        }
    }
}
