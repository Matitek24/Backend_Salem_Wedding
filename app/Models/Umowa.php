<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Umowa extends Model
{
    use HasFactory;

    protected $table = 'umowy';

    protected $fillable = [
        'wedding_id',
        'imie',
        'nazwisko',
        'pesel',
        'nr_dowodu',
        'pakiet',
        'adres',
        'telefon_mlodego',
        'data',
        'data_final',
        'stawka',
        'zadatek',
        'dron',
        'plik_umowy',
        'data_podpisania',
        'status',
        'sala',
        'koscol',
    ];

    protected $attributes = [
        'wedding_id'       => null,
        'imie'             => '',
        'nazwisko'         => '',
        'pesel'            => '',
        'nr_dowodu'        => '',
        'pakiet'           => '',
        'adres'            => '',
        'telefon_mlodego'  => '',
        'data'             => '2000-01-01', // Domyślna przykładowa data
        'data_final'       => '2000-01-01',
        'stawka'           => 0.00, // Domyślnie 0 jako wartość numeryczna
        'zadatek'          => 0.00,
        'dron'             => false, // Domyślnie brak drona (false)
        'plik_umowy'       => null, // Domyślnie brak pliku
        'data_podpisania'  => null,
        'status'           => 'utworzona', // Domyślny status
        'sala'             => '',
        'koscol'           => '',
    ];
    
    public function wedding()
    {
        return $this->belongsTo(Wedding::class);
    }

    protected static function booted()
    {
        // Logika wywoływana przy każdym zapisie (tworzenie lub aktualizacja)
        static::saving(function ($record) {
            // Upewnij się, że stawka jest ustawiona; jeśli nie, ustaw domyślną wartość 0.00
            $record->stawka = $record->stawka ?: 0.00;
            
            // Oblicz zadatek jako 20% stawki - zawsze nadpisujemy przekazaną wartość
            $record->zadatek = $record->stawka * 0.2;

            // Ustaw datę finalną jako datę z pola "data" powiększoną o 4 miesiące, niezależnie od danych z frontendu
            $record->data_final = Carbon::parse($record->data)->addMonths(4)->toDateString();
        });

        // Logika usuwania starego pliku przy aktualizacji pola plik_umowy
        static::updating(function ($record) {
            if ($record->isDirty('plik_umowy')) {
                $originalFile = $record->getOriginal('plik_umowy');
                if ($originalFile && Storage::disk('public')->exists($originalFile)) {
                    Storage::disk('public')->delete($originalFile);
                }
            }
        });
    }
}
