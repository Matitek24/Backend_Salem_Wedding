<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use WebPConvert\WebPConvert;
use App\Models\Banner;

class OptimizeBannerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $banner;

    public function __construct(Banner $banner)
    {
        $this->banner = $banner;
    }

    public function handle()
    {
        set_time_limit(300); // Maksymalny czas wykonania: 5 minut

        $originalPath = storage_path('app/public/' . $this->banner->image);

        // Sprawdzamy, czy plik istnieje i czy nie jest już w formacie WebP
        if (file_exists($originalPath) && strtolower(pathinfo($originalPath, PATHINFO_EXTENSION)) !== 'webp') {
            $directory = pathinfo($originalPath, PATHINFO_DIRNAME);
            $filename = pathinfo($originalPath, PATHINFO_FILENAME);
            $outputPath = $directory . '/' . $filename . '.webp';

            try {
                // Konwersja do WebP z jakością 50%
                WebPConvert::convert($originalPath, $outputPath, [
                    'quality' => 50,
                ]);

                // Usuwamy oryginalny plik
                if (file_exists($originalPath)) {
                    unlink($originalPath);
                }

                // Aktualizacja ścieżki w modelu, aby wskazywała na plik .webp
                $this->banner->image = preg_replace('/\.[^.]+$/', '.webp', $this->banner->image);
                $this->banner->saveQuietly();
            } catch (\Exception $e) {
                \Log::error('Błąd konwersji obrazu do WebP: ' . $e->getMessage());
            }
        }
    }
}
