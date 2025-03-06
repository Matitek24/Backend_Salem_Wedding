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
        'liczba_gosci'
    ];
    protected $attributes = [
        'sala' => '',
        'koscol' => '',
        'liczba_gosci' => 0,
        'typ_wesela' => '',
    ];
    
}
