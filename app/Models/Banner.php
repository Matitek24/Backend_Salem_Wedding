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

        static::saved(function ($banner) {
            if ($banner->image) {
                $originalPath = storage_path('app/public/' . $banner->image);
                $directory = pathinfo($originalPath, PATHINFO_DIRNAME);
                $filename = pathinfo($originalPath, PATHINFO_FILENAME);
                $outputPath = $directory . '/' . $filename . '.webp';

                try {
                   
                    WebPConvert::convert($originalPath, $outputPath, [
                        'quality' => 50,
                    ]);
                    if (file_exists($originalPath)) {
                        unlink($originalPath);
                    }
                    // Aktualizacja ścieżki w modelu, aby wskazywała na plik .webp
                    $banner->image = str_replace(pathinfo($originalPath, PATHINFO_EXTENSION), 'webp', $banner->image);
                    $banner->saveQuietly();
                } catch (\Exception $e) {
                    \Log::error('Błąd konwersji obrazu do webp: ' . $e->getMessage());
                }
            }
        });

        static::deleting(function ($banner) {
            if ($banner->image) {
                Storage::delete("public/{$banner->image}");
            }
        });
    }
}
