<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\LocalityController;
use App\Http\Controllers\Api\DeveloperMapStorageController;

// Contact form API
Route::post('/contact', [ContactController::class, 'store']);

// Developer Map API - Projects
Route::apiResource('projects', ProjectController::class);
Route::get('projects/map/{mapKey}', [ProjectController::class, 'showByMapKey']);

// Developer Map API - Localities (nested under projects)
Route::prefix('projects/{project}')->group(function () {
    Route::get('localities', [LocalityController::class, 'index']);
    Route::post('localities', [LocalityController::class, 'store']);
    Route::get('localities/{locality}', [LocalityController::class, 'show']);
    Route::put('localities/{locality}', [LocalityController::class, 'update']);
    Route::delete('localities/{locality}', [LocalityController::class, 'destroy']);
    Route::patch('localities/bulk', [LocalityController::class, 'bulkUpdate']);
});

// Developer Map Storage API (compatible with dm.js storage-client)
Route::prefix('developer-map')->group(function () {
    Route::get('list', [DeveloperMapStorageController::class, 'list']);
    Route::get('get', [DeveloperMapStorageController::class, 'get']);
    Route::post('set', [DeveloperMapStorageController::class, 'set']);
    Route::delete('remove', [DeveloperMapStorageController::class, 'remove']);
    Route::post('migrate', [DeveloperMapStorageController::class, 'migrate']);
    Route::post('image', [DeveloperMapStorageController::class, 'saveImage']);
    Route::get('bootstrap', [DeveloperMapStorageController::class, 'bootstrap']);
});

// User route (default)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

