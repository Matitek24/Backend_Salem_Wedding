<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogPostController extends Controller
{
    /**
     * Pobierz listę wpisów bloga.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $posts = BlogPost::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(4);
        
        return JsonResource::collection($posts);
    }

    /**
     * Pobierz pojedynczy wpis bloga.
     *
     * @param  int  $id
     * @return JsonResource
     */
    public function show($id)
    {
        $post = BlogPost::findOrFail($id);
        
        return new JsonResource($post);
    }
}