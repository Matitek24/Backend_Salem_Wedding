<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\OptimizeWeddingImageJob;

class Wedding extends Model
{
    use HasFactory;

    protected $fillable = [
        'imie1',
        'nazwisko1', 
        'imie2',
        'nazwisko2',
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

    public function umowy()
    {
        return $this->hasMany(Umowa::class);
    }

    protected static function booted()
    {
        static::saved(function ($record) {
            if ($record->photo) {
                dispatch(new OptimizeWeddingImageJob($record));
            }
        });
    }
}
