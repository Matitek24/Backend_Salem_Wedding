<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use WebPConvert\WebPConvert;
use App\Models\GalleryImage;

class OptimizeImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $galleryImage;
    
    public function __construct(GalleryImage $galleryImage)
    {
        $this->galleryImage = $galleryImage;
    }
    
    public function handle()
    {
        $originalPath = storage_path('app/public/' . $this->galleryImage->image_path);
        // Sprawdzamy, czy plik istnieje oraz nie jest już WebP
        if (file_exists($originalPath) && strtolower(pathinfo($originalPath, PATHINFO_EXTENSION)) !== 'webp') {
            $directory = pathinfo($originalPath, PATHINFO_DIRNAME);
            $filename = pathinfo($originalPath, PATHINFO_FILENAME);
            $outputPath = $directory . '/' . $filename . '.webp';
            
            try {
                // Konwersja do WebP z jakością 50%
                WebPConvert::convert($originalPath, $outputPath, [
                    'converter' => 'cwebp',
                    'quality' => 50,
                ]);
                
                // Usuwamy oryginalny plik
                if (file_exists($originalPath)) {
                    unlink($originalPath);
                }
                
                // Aktualizujemy ścieżkę w modelu
                $this->galleryImage->image_path = preg_replace('/\.[^.]+$/', '.webp', $this->galleryImage->image_path);
                $this->galleryImage->saveQuietly();
            } catch (\Exception $e) {
                \Log::error('Błąd konwersji obrazu do WebP: ' . $e->getMessage());
            }
        }
    }
}
