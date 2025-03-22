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
    ];

    protected static function boot()
    {
        parent::boot();


        static::deleting(function ($banner) {
            if ($banner->image) {
                Storage::delete("public/{$banner->image}");
            }
        });
    }
}
