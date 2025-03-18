<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'typ_zamowienia' => 'rezerwacja', // domyślnie ustawione na rezerwacja
    ];
}
