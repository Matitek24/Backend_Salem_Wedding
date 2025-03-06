<?php
namespace App\Filament\Widgets;

use App\Models\Wedding;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Carbon\Carbon;

class WeddingCalendar extends FullCalendarWidget
{
    protected static ?string $heading = 'Kalendarz Wesel';

    public function fetchEvents(array $fetchInfo): array
    {
        $start = $fetchInfo['start'] ?? null;
        $end = $fetchInfo['end'] ?? null;

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

    // Usuwamy akcję edycji – pozostawiamy tylko CreateAction
    public function getActions(): array
    {
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

    protected function createEvent(array $data): \Illuminate\Database\Eloquent\Model
    {
        return Wedding::create([
            'date' => $data['start'],
        ]);
    }

    protected function editEvent(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $record->update([
            'date' => $data['start'],
        ]);
        return $record;
    }

    protected function updateEvent(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $record->update([
            'date' => $data['start'],
        ]);
        return $record;
    }

    public function eventDidMount(): string
    {
        return <<<JS
            function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }){
                el.setAttribute("x-tooltip", "tooltip");
                el.setAttribute("x-data", "{ tooltip: '"+event.title+"' }");
                // Blokujemy kliknięcie, zatrzymując propagację zdarzenia
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });
            }
        JS;
    }
    

    public function config(): array
    {
        return [
            'events' => $this->fetchEvents([]),
            'select' => true,
            'selectHelper' => true,
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
}
