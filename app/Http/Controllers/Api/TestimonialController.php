<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialController extends Controller
{
    /**
     * Pobierz listę opinii klientów.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $testimonials = Testimonial::orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return JsonResource::collection($testimonials);
    }

    /**
     * Pobierz pojedynczą opinię klienta.
     *
     * @param int $id
     * @return JsonResource
     */
    public function show($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return new JsonResource($testimonial);
    }

    /**
     * Pobierz tylko wyróżnione opinie.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function featured()
    {
        $testimonials = Testimonial::where('is_featured', true)
            ->orderBy('order', 'asc')
            ->get();

        return JsonResource::collection($testimonials);
    }
}