<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WeddingStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Services\FileUploadService;

class WeddingStoryController extends Controller
{
    public function index()
    {
        return response()->json(WeddingStory::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'couple_names'   => 'required|string|max:255',
            'description'    => 'required|string',
            'thumbnail'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'youtube_link'   => 'nullable|url',
            'gallery_link'   => 'nullable|url',
            'access_code'    => 'required|string|max:255',
        ]);

        // Hashujemy access_code przed zapisem
        $validated['access_code'] = Hash::make($validated['access_code']);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = FileUploadService::uploadThumbnail($request->file('thumbnail'));
        }

        $story = WeddingStory::create($validated);

        return response()->json($story, 201);
    }

    public function show(WeddingStory $weddingStory)
    {
        return response()->json($weddingStory);
    }

    public function update(Request $request, WeddingStory $weddingStory)
    {
        $validated = $request->validate([
            'couple_names'   => 'sometimes|string|max:255',
            'description'    => 'sometimes|string',
            'thumbnail'      => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'youtube_link'   => 'nullable|url',
            'gallery_link'   => 'nullable|url',
            'access_code'    => 'sometimes|string|max:255',
        ]);

        // Jeśli podano nowy kod, hashujemy go przed zapisem
        if ($request->has('access_code')) {
            $validated['access_code'] = Hash::make($validated['access_code']);
        }

        if ($request->hasFile('thumbnail')) {
            if ($weddingStory->thumbnail) {
                Storage::disk('public')->delete($weddingStory->thumbnail);
            }
            $validated['thumbnail'] = FileUploadService::uploadThumbnail($request->file('thumbnail'));
        }

        $weddingStory->update($validated);

        return response()->json($weddingStory);
    }

    public function destroy(WeddingStory $weddingStory)
    {
        // Usuwamy plik przy wywołaniu kontrolera
        if ($weddingStory->thumbnail) {
            Storage::disk('public')->delete($weddingStory->thumbnail);
        }

        $weddingStory->delete();

        return response()->json(null, 204);
    }
}
