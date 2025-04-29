<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'logo_image',
        'left_image',
        'primary_button_text',
        'main_offer_text',
        'cta_button_text',
        'website_url',
        'instagram_url',
        'youtube_url',
        'facebook_url',
        'is_active',
        'sort_order',
    ];

    protected static function boot()
    {
        parent::boot();

        // Delete images when model is deleted
        static::deleting(function ($recommendation) {
            if ($recommendation->logo_image) {
                Storage::disk('public')->delete($recommendation->logo_image);
            }
            
            if ($recommendation->left_image) {
                Storage::disk('public')->delete($recommendation->left_image);
            }
        });
    }
}