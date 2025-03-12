<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'image_path', 'order'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
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
