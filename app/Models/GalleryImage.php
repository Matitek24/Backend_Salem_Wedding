<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryImage extends Model
{
    protected $fillable = ['image_path', 'order', 'category_id']; 

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            $maxOrder = static::where('category_id', $image->category_id)->max('order');
            $image->order = $maxOrder + 1;
        });

        static::deleting(function ($image) {
            if ($image->image_path) {
                Storage::delete("public/{$image->image_path}");
            }
        });
    }
}
