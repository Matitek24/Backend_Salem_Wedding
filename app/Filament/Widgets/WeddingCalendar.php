<?php
namespace App\Filament\Widgets;

use App\Models\Wedding;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;

class WeddingCalendar extends FullCalendarWidget
{
    protected static ?string $heading = 'Kalendarz Wesel';

    // Metoda do pobierania wydarzeń
    public function fetchEvents(array $fetchInfo): array
    {
        $start = isset($fetchInfo['start']) ? $fetchInfo['start'] : null;
        $end = isset($fetchInfo['end']) ? $fetchInfo['end'] : null;

        if ($start && $end) {
            return Wedding::query()
                ->where('date', '>=', $start) // Filtrujemy wesela po dacie początkowej
                ->where('date', '<=', $end)   // Filtrujemy wesela po dacie końcowej
                ->get()
                ->map(fn (Wedding $wedding) => [
                    'id'    => (string) $wedding->id,
                    'title' => 'Wesele ' . $wedding->id,
                    'start' => \Carbon\Carbon::parse($wedding->date)->format('Y-m-d'),
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
