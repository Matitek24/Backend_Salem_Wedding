<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use WebPConvert\WebPConvert;

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

        // static::saved(function ($image) {
        //     if ($image->image_path) {
        //         // Dispatch joba do optymalizacji obrazu
        //         dispatch(new \App\Jobs\OptimizeImageJob($image));
        //     }
        // });

        static::deleting(function ($image) {
            if ($image->image_path) {
                Storage::delete("public/{$image->image_path}");
            }
        });
    }
}
