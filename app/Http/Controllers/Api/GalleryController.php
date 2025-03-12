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
    try {

        $galleryImages = GalleryImage::with('category')
            ->orderBy('category_id')
            ->orderBy('order')
            ->get()
            ->map(function ($image) {
                $image->image_path = $image->image_path ? url('storage/' . $image->image_path) : null;
                return $image;
            });


        return response()->json($galleryImages);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Błąd serwera',
            'message' => $e->getMessage()
        ], 500);
    }
}

}
