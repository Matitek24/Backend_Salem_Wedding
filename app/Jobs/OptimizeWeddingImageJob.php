<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use WebPConvert\WebPConvert;
use App\Models\Wedding;
use Illuminate\Support\Facades\Storage;

class OptimizeWeddingImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $wedding;

    // Przekazujemy obiekt Wedding do joba
    public function __construct(Wedding $wedding)
    {
        $this->wedding = $wedding;
    }

    public function handle()
    {
        set_time_limit(300); // Dajemy więcej czasu na proces

        // Ścieżka do zdjęcia
        $originalPath = storage_path('app/public/' . $this->wedding->photo);

        // Sprawdzamy, czy plik istnieje i nie jest już w formacie WebP
        if (file_exists($originalPath) && strtolower(pathinfo($originalPath, PATHINFO_EXTENSION)) !== 'webp') {
            $directory = pathinfo($originalPath, PATHINFO_DIRNAME);
            $filename = pathinfo($originalPath, PATHINFO_FILENAME);
            $outputPath = $directory . '/' . $filename . '.webp';

            try {
                // Konwersja do WebP z jakością 30%
                WebPConvert::convert($originalPath, $outputPath, [
                    'converter' => 'cwebp',
                    'quality' => 30,
                ]);

                // Usuwamy oryginalny plik
                if (file_exists($outputPath)) {
                    unlink($originalPath);
                }

                // Zaktualizuj ścieżkę w bazie
                $this->wedding->photo = preg_replace('/\.[^.]+$/', '.webp', $this->wedding->photo);
                $this->wedding->saveQuietly();
            } catch (\Exception $e) {
                \Log::error('Błąd konwersji zdjęcia do WebP: ' . $e->getMessage());
            }
        }
    }
}
