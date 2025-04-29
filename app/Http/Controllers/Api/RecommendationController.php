<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationController extends Controller
{
    /**
     * Get all recommendations or filter by category.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = Recommendation::where('is_active', true)
            ->orderBy('sort_order', 'asc');
            
        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        $recommendations = $query->get();
        
        return JsonResource::collection($recommendations);
    }

    /**
     * Get all unique categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories()
    {
        $categories = Recommendation::where('is_active', true)
            ->distinct()
            ->pluck('category');
            
        return response()->json($categories);
    }

    /**
     * Get a specific recommendation.
     *
     * @param int $id
     * @return JsonResource
     */
    public function show($id)
    {
        $recommendation = Recommendation::findOrFail($id);
        return new JsonResource($recommendation);
    }
}