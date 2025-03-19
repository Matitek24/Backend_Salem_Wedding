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
        $publicStories = WeddingStory::where('is_public', true)
            ->orderBy('order') // Dodane sortowanie
            ->get()
            ->map(function ($story) {
                $story->thumbnail = $story->thumbnail ? url('storage/' . $story->thumbnail) : null;
                return $story;
            });
            
        $privateStories = WeddingStory::where('is_public', false)
            ->get()
            ->map(function ($story) {
                $story->thumbnail = $story->thumbnail ? url('storage/' . $story->thumbnail) : null;
                return $story;
            });
            
        return response()->json([
            'public_stories' => $publicStories,
            'private_stories' => $privateStories,
        ]);
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

        if ($request->hasFile('thumbnail')) {
            if ($weddingStory->thumbnail) {
                Storage::disk('public')->delete($weddingStory->thumbnail);
            }
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

    public function checkAccessCode(Request $request, $id)
    {
        $weddingStory = WeddingStory::find($id);

        if (!$weddingStory) {
            return response()->json(['message' => 'Historia nie istnieje'], 404);
        }

        $inputCode = $request->input('access_code');

        try {
            $decryptedCode = $weddingStory->access_code; // Model automatycznie deszyfruje kod
            if ($inputCode === $decryptedCode) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Błędny kod'], 403);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Błąd deszyfrowania '], 500);
        }
    }
}
