<?php
namespace App\Filament\Widgets;

use App\Models\Wedding;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WeddingCalendar extends FullCalendarWidget
{
    protected static ?string $heading = 'Kalendarz Wesel';

    // Metoda do pobierania wydarzeń
    public function fetchEvents(array $fetchInfo): array
{
    $start = $fetchInfo['start'] ?? null;
    $end = $fetchInfo['end'] ?? null;

    if ($start && $end) {
        return Wedding::query()
            ->where('data', '>=', $start) // Filtrujemy wesela po dacie początkowej
            ->where('data', '<=', $end)   // Filtrujemy wesela po dacie końcowej
            ->get()
            ->map(fn (Wedding $wedding) => [
                'id'    => (string) $wedding->id,
                'title' => "{$wedding->imie1} & {$wedding->imie2} - {$wedding->sala}",
                'start' => Carbon::parse($wedding->data)->format('Y-m-d'),
                'allDay' => true, // Zapewnia, że wydarzenie trwa cały dzień
                'color' => empty($wedding->sala) || empty($wedding->typ_wesela) || empty($wedding->koscol) || empty($wedding->liczba_gosci)
                    ? '#5F61FF'  // Pomarańczowy (jeśli brakuje danych)
                    : 'green', // Niebieski (jeśli wszystko jest uzupełnione)
            ])
            ->toArray();
    }

    return [];
}

    // Dodajemy akcję CreateAction dla dodawania wesela po kliknięciu na kalendarz
    public function getActions(): array
    {
        return [
            CreateAction::make()->modalHeading('Dodaj Wesele')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('date')
                        ->label('Data wesela')
                        ->required()
                        ->default(now()->toDateString()), // Domyślna data na dzisiaj
                ])
                ->mutateFormDataUsing(fn (array $data): array => [
                    'date' => $data['date'],
                ])
                ->action(fn (array $data) => Wedding::create([
                    'date' => $data['date'],
                ])),
        ];
    }

    // Akcje do tworzenia, edytowania i aktualizowania wydarzeń
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

    // Ustawiamy konfigurację kalendarza
    public function config(): array
    {
        return [
            'events' => $this->fetchEvents([]),  // Zgodnie z metodą fetchEvents
            'select' => true,  // Możliwość kliknięcia w datę
            'selectHelper' => true,  // Pomaga przy kliknięciu
            'selectOverlap' => false,  // Nie pozwala na nakładanie się wydarzeń
        ];
    }
}
