<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use WebPConvert\WebPConvert;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'page',
        'sort_order',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    protected static function boot()
    {
        parent::boot();


        static::deleting(function ($banner) {
            if ($banner->image) {
                Storage::delete("public/{$banner->image}");
            }
            static::creating(function ($banner) {
                if (!$banner->sort_order) {
                    $banner->sort_order = self::where('page', $banner->page)->max('sort_order') + 1;
                }
            });
            
            // When updating a banner and page changes, handle sort_order adjustments
            static::updating(function ($banner) {
                if ($banner->isDirty('page')) {
                    $oldPage = $banner->getOriginal('page');
                    $newPage = $banner->page;
                    $oldOrder = $banner->getOriginal('sort_order');
                    
                    // Reorder old page banners
                    self::where('page', $oldPage)
                        ->where('sort_order', '>', $oldOrder)
                        ->decrement('sort_order');
                        
                    // Set new sort_order for new page
                    $banner->sort_order = self::where('page', $newPage)->max('sort_order') + 1;
                }
            });
        });
    }
}
