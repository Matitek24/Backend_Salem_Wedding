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
