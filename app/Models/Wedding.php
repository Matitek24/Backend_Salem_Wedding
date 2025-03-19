<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\OptimizeWeddingImageJob; // Zaimportuj nowy Job

class Wedding extends Model
{
    use HasFactory;

    protected $fillable = [
        'imie1',
        'imie2',
        'data',
        'typ_wesela',
        'sala',
        'koscol',
        'liczba_gosci',
        'telefon_panny',
        'telefon_pana',
        'pakiet',
        'typ_zamowienia',
        'uwagi',
        'photo',
    ];

    protected $attributes = [
        'sala' => '',
        'koscol' => '',
        'liczba_gosci' => 0,
        'typ_wesela' => '',
        'telefon_panny' => '',
        'telefon_pana' => '',
        'pakiet' => '',
        'uwagi' => '',
        'typ_zamowienia' => 'rezerwacja',
    ];

    // Dodajemy wywołanie Job po zapisaniu rekordu
    protected static function booted()
    {
        static::saved(function ($record) {
            if ($record->photo) {
                // Wywołanie Job'a po zapisaniu
                dispatch(new OptimizeWeddingImageJob($record));
            }
        });
    }
}
