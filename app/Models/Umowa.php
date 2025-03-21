<?php

// app/Models/Umowa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'sala',
        'koscol',
    ];

    // Relacja z modelem Wedding
    public function wedding()
    {
        return $this->belongsTo(Wedding::class);
    }
    protected static function booted()
    {
        static::updating(function ($record) {
            // Jeśli pole 'plik_umowy' zostało zmienione – czyli usunięte (np. z wartości na null)
            if ($record->isDirty('plik_umowy')) {
                $originalFile = $record->getOriginal('plik_umowy');
                if ($originalFile && Storage::disk('public')->exists($originalFile)) {
                    Storage::disk('public')->delete($originalFile);
                }
            }
        });
    }
}