<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class FileUploadService
{
    public static function uploadThumbnail(UploadedFile $file): string
    {
        // Plik zostanie zapisany w katalogu storage/app/public/uploads/wedding_thumbnails
        // Upewnij się, że masz utworzony link symboliczny (php artisan storage:link)
        return $file->store('storage/uploads/wedding_thumbnails', 'public');
    }
}
