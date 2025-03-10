<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WeddingStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class WeddingStoryController extends Controller
{
    public function index()
    {
        $stories = WeddingStory::all()->map(function ($story) {
            $story->thumbnail = $story->thumbnail ? url('storage/' . $story->thumbnail) : null;
            return $story;
        });

        return response()->json($stories);
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

        $validated['access_code'] = Crypt::encryptString($validated['access_code']);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('uploads/wedding_thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        $story = WeddingStory::create($validated);
        $story->thumbnail = url('storage/' . $story->thumbnail);

        return response()->json($story, 201);
    }

    public function show(WeddingStory $weddingStory)
    {
        $weddingStory->thumbnail = $weddingStory->thumbnail ? url('storage/' . $weddingStory->thumbnail) : null;

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

        if ($request->has('access_code')) {
            $validated['access_code'] = Crypt::encryptString($validated['access_code']);
        }

        if ($request->hasFile('thumbnail')) {
            // Usunięcie starego pliku, jeśli istnieje
            if ($weddingStory->thumbnail) {
                Storage::disk('public')->delete($weddingStory->thumbnail);
            }

            // Przechowywanie nowego zdjęcia
            $path = $request->file('thumbnail')->store('uploads/wedding_thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        $weddingStory->update($validated);
        $weddingStory->thumbnail = $weddingStory->thumbnail ? url('storage/' . $weddingStory->thumbnail) : null;

        return response()->json($weddingStory);
    }

    public function destroy(WeddingStory $weddingStory)
    {
        if ($weddingStory->thumbnail) {
            Storage::disk('public')->delete($weddingStory->thumbnail);
        }

        $weddingStory->delete();

        return response()->json(null, 204);
    }
}
