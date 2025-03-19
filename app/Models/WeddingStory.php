<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class WeddingStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'couple_names',
        'description',
        'thumbnail',
        'youtube_link',
        'gallery_link',
        'access_code',
        'is_public',
        'promo_link',
        'order',
    ];
    

    // Automatyczne szyfrowanie przy zapisie
    public function setAccessCodeAttribute($value)
    {
        $this->attributes['access_code'] = Crypt::encryptString($value);
    }

    // Automatyczne odszyfrowanie przy odczycie
    public function getAccessCodeAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }
}
