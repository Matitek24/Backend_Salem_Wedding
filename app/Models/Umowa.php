<?php

// app/Models/Umowa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'adres',
        'nip',
        'telefon_mlodego',
        'telefon_mlodej',
        'plik_umowy',
        'data_podpisania',
        'status',
    ];

    // Relacja z modelem Wedding
    public function wedding()
    {
        return $this->belongsTo(Wedding::class);
    }
}