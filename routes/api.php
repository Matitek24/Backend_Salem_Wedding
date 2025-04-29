<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\WeddingController;
use App\Http\Controllers\Api\WeddingStoryController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\UmowaController;
use App\Http\Controllers\Api\BlogPostController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\RecommendationController;

Route::post('/submit-form', [FormController::class, 'store']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/check-availability', [WeddingController::class, 'checkAvailability']);

Route::apiResource('wedding-stories', WeddingStoryController::class);



Route::prefix('wedding-stories')->group(function () {
    Route::get('/', [WeddingStoryController::class, 'index']);            // Pobiera wszystkie historie
    Route::post('/', [WeddingStoryController::class, 'store']);           // Dodaje nową historię
    Route::get('/{weddingStory}', [WeddingStoryController::class, 'show']); // Pobiera jedną historię
    Route::put('/{weddingStory}', [WeddingStoryController::class, 'update']); // Aktualizuje historię
    Route::delete('/{weddingStory}', [WeddingStoryController::class, 'destroy']); // Usuwa historię
});
Route::post('/wedding-stories/{id}/check-access', [WeddingStoryController::class, 'checkAccessCode']);



Route::get('/gallery', [GalleryController::class, 'index']);



Route::get('/banners', [BannerController::class, 'index']); // Pobiera listę banerów
Route::post('/banners', [BannerController::class, 'store']); // Dodaje nowy baner
Route::delete('/banners/{id}', [BannerController::class, 'destroy']); // Usuwa baner



Route::get('/weddings/{id}', [WeddingController::class, 'show']);
Route::post('/umowy', [UmowaController::class, 'store']);

Route::get('/weddings/{wedding_id}', [WeddingController::class, 'show'])
    ->middleware('signed');

Route::get('/blog', [BlogPostController::class, 'index']);
Route::get('/blog/{id}', [BlogPostController::class, 'show']);

Route::get('/testimonials', [TestimonialController::class, 'index']);
Route::get('/testimonials/featured', [TestimonialController::class, 'featured']);
Route::get('/testimonials/{id}', [TestimonialController::class, 'show']);

Route::get('/recommendations', [RecommendationController::class, 'index']);
Route::get('/recommendations/categories', [RecommendationController::class, 'categories']);
Route::get('/recommendations/{id}', [RecommendationController::class, 'show']);