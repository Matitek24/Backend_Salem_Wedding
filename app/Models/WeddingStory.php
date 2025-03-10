<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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
    ];

    // Automatycznie szyfruje access_code przy zapisie
    public function setAccessCodeAttribute($value)
    {
        $this->attributes['access_code'] = Crypt::encryptString($value);
    }

    // Automatycznie odszyfrowuje access_code przy odczycie
    public function getAccessCodeAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    protected static function booted()
    {
        static::deleting(function ($story) {
            if ($story->thumbnail) {
                Storage::disk('public')->delete($story->thumbnail);
            }
        });
    }
}
