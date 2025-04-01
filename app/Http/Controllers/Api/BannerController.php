<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class BannerController extends Controller
{
    // Pobieranie wszystkich banerów z uwzględnieniem kolejności
    public function index()
    {
        $banners = Banner::orderBy('page')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'image_url' => asset('storage/' . $banner->image),
                    'page' => $banner->page,
                    'sort_order' => $banner->sort_order,
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

        // Przypisanie pozycji na końcu dla danej podstrony
        $maxOrder = Banner::where('page', $request->page)->max('sort_order') ?? 0;
        $nextOrder = $maxOrder + 1;

        // Przechowywanie obrazu w storage/app/public/banners
        $path = $request->file('image')->store('banners', 'public');

        // Zapis do bazy
        $banner = Banner::create([
            'image' => $path,
            'page' => $request->page,
            'sort_order' => $nextOrder,
        ]);

        return response()->json([
            'message' => 'Baner dodany pomyślnie!',
            'banner' => [
                'id' => $banner->id,
                'image_url' => asset('storage/' . $banner->image),
                'page' => $banner->page,
                'sort_order' => $banner->sort_order,
            ],
        ], 201);
    }

    // Aktualizacja kolejności banerów
    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:banners,id',
            'orders.*.sort_order' => 'required|integer|min:1',
            'page' => 'required|string',
        ]);

        // Get all banners for the specified page
        $banners = Banner::where('page', $request->page)->get();
        
        // Create mapping of id to new order
        $orderMap = collect($request->orders)->pluck('sort_order', 'id');
        
        // Update each banner's sort_order
        foreach ($banners as $banner) {
            if ($orderMap->has($banner->id)) {
                $banner->sort_order = $orderMap->get($banner->id);
                $banner->save();
            }
        }

        return response()->json(['message' => 'Kolejność banerów zaktualizowana!']);
    }

    // Usuwanie banera
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        
        // Zapamiętanie strony i pozycji przed usunięciem
        $page = $banner->page;
        $sortOrder = $banner->sort_order;
        
        // Usunięcie pliku z dysku
        Storage::disk('public')->delete($banner->image);
        
        // Usunięcie z bazy
        $banner->delete();
        
        // Aktualizacja kolejności pozostałych banerów
        Banner::where('page', $page)
            ->where('sort_order', '>', $sortOrder)
            ->decrement('sort_order');
        
        return response()->json(['message' => 'Baner usunięty!'], 200);
    }
}