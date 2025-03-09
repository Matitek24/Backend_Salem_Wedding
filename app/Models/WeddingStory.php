<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    protected static function booted()
    {
        static::deleting(function ($story) {
            if ($story->thumbnail) {
                Storage::disk('public')->delete($story->thumbnail);
            }
        });
    }
}
