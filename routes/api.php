<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\WeddingController;
use App\Http\Controllers\Api\WeddingStoryController;


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
