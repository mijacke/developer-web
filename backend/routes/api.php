<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApartmentController;
use App\Http\Controllers\Api\ContactController;

// Apartments CRUD API
Route::apiResource('apartments', ApartmentController::class);

// Contact form API
Route::post('/contact', [ContactController::class, 'store']);

// User route (default)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
