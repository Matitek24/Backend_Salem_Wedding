<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'content',
        'image_position',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    // Event hooks - automatyczne usuwanie zdjęcia z dysku po usunięciu modelu
    protected static function boot()
    {
        parent::boot();

        // Przed usunięciem modelu usuwamy powiązane zdjęcie
        static::deleting(function ($testimonial) {
            if ($testimonial->image && Storage::disk('public')->exists($testimonial->image)) {
                Storage::disk('public')->delete($testimonial->image);
            }
        });
    }
}