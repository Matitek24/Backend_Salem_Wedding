<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Zwraca wszystkie zdjęcia galerii z kategoriami i kolejnością
     */
    public function index()
    {
        $galleryImages = GalleryImage::with('category')
            ->orderBy('category_id')
            ->orderBy('order')
            ->get();

        return response()->json($galleryImages);
    }
}
