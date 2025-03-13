<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class BannerController extends Controller
{
    // Pobieranie wszystkich banerów
    public function index()
    {
        $banners = Banner::all()->map(function ($banner) {
            return [
                'id' => $banner->id,
                'image_url' => asset('storage/' . $banner->image),
                'page' => $banner->page,
            ];
        });

        return response()->json($banners);
    }

    // Przesyłanie nowego banera
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', File::image()->max(10 * 1024)], // max 10MB
            'page' => 'required|string|max:255',
        ]);

        // Przechowywanie obrazu w storage/app/public/banners
        $path = $request->file('image')->store('banners', 'public');

        // Zapis do bazy
        $banner = Banner::create([
            'image' => $path,
            'page' => $request->page,
        ]);

        return response()->json([
            'message' => 'Baner dodany pomyślnie!',
            'banner' => [
                'id' => $banner->id,
                'image_url' => asset('storage/' . $banner->image),
                'page' => $banner->page,
            ],
        ], 201);
    }

    // Usuwanie banera
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        // Usunięcie pliku z dysku
        Storage::disk('public')->delete($banner->image);

        // Usunięcie z bazy
        $banner->delete();

        return response()->json(['message' => 'Baner usunięty!'], 200);
    }
}
