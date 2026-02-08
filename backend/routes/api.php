<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\LocalityController;
use App\Http\Controllers\Api\DeveloperMapReadController;
use App\Http\Controllers\Api\DeveloperMapWriteController;
use App\Http\Controllers\Api\DeveloperMapMediaController;
use App\Http\Controllers\Api\DeveloperMapPaletteController;

/*
|--------------------------------------------------------------------------
| Public Routes (no authentication required)
|--------------------------------------------------------------------------
*/

// Contact form API - rate limited to 5 requests per minute per IP (with violation logging)
Route::middleware('throttle_log:5,1')->post('/contact', [ContactController::class, 'store']);

// Developer Map viewer (public, used by frontend map-viewer.js)
Route::get('/developer-map/viewer', [\App\Http\Controllers\Api\DeveloperMapViewerController::class, 'show']);

// Public Developer Map API - Read Only
Route::get('projects', [ProjectController::class, 'index']);
Route::get('projects/{project}', [ProjectController::class, 'show']);
Route::get('projects/map/{mapKey}', [ProjectController::class, 'showByMapKey']);

Route::prefix('projects/{project}')->group(function () {
    Route::get('localities', [LocalityController::class, 'index']);
    Route::get('localities/{locality}', [LocalityController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    // Public auth routes with rate limiting
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });

    // Forgot password - stricter rate limiting (3 per hour)
    Route::middleware('throttle:3,60')->group(function () {
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
    });

    // Protected auth routes
    Route::middleware(['auth:sanctum', 'not_blocked'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

/*
|--------------------------------------------------------------------------
| Protected Routes (require authentication + approval)
|--------------------------------------------------------------------------
| All authenticated and approved users can access CRUD operations.
| Login already checks is_approved, so authenticated = approved.
*/

Route::middleware(['auth:sanctum', 'not_blocked'])->group(function () {
    // User info (for Sanctum SPA)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Projects
    Route::post('projects', [ProjectController::class, 'store']);
    Route::put('projects/{project}', [ProjectController::class, 'update']);
    Route::delete('projects/{project}', [ProjectController::class, 'destroy']);

    // Localities
    Route::prefix('projects/{project}')->group(function () {
        Route::post('localities', [LocalityController::class, 'store']);
        Route::put('localities/{locality}', [LocalityController::class, 'update']);
        Route::delete('localities/{locality}', [LocalityController::class, 'destroy']);
        Route::patch('localities/bulk', [LocalityController::class, 'bulkUpdate']);
    });

    // Developer Map Storage API (all authenticated users)
    Route::prefix('developer-map')->group(function () {
        Route::get('list', [DeveloperMapReadController::class, 'list']);
        Route::get('get', [DeveloperMapReadController::class, 'get']);
        Route::post('set', [DeveloperMapWriteController::class, 'set']);
        Route::delete('remove', [DeveloperMapWriteController::class, 'remove']);
        Route::post('migrate', [DeveloperMapWriteController::class, 'migrate']);
        Route::post('image', [DeveloperMapMediaController::class, 'saveImage']);
        Route::post('upload', [DeveloperMapMediaController::class, 'uploadImage']);
        Route::get('bootstrap', [DeveloperMapReadController::class, 'bootstrap']);

        // Delete endpoints for types and statuses
        Route::delete('types/{id}', [DeveloperMapPaletteController::class, 'deleteType']);
        Route::delete('statuses/{id}', [DeveloperMapPaletteController::class, 'deleteStatus']);
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes (require admin role)
|--------------------------------------------------------------------------
| Only admins can access user management and dashboard.
*/

Route::middleware(['auth:sanctum', 'not_blocked', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('stats', [AdminController::class, 'stats']);
    Route::get('users', [AdminController::class, 'users']);
    Route::post('users/{id}/approve', [AdminController::class, 'approveUser']);
    Route::post('users/{id}/reject', [AdminController::class, 'rejectUser']);
    Route::post('users/{id}/promote', [AdminController::class, 'promoteUser']);
    Route::post('users/{id}/demote', [AdminController::class, 'demoteUser']);
    Route::post('users/{id}/block', [AdminController::class, 'blockUser']);
    Route::post('users/{id}/unblock', [AdminController::class, 'unblockUser']);
    Route::delete('users/{id}', [AdminController::class, 'deleteUser']);
    Route::get('audit-logs', [AdminController::class, 'auditLogs']);
    
    // Contact messages management
    Route::get('contact-messages', [AdminController::class, 'contactMessages']);
    Route::post('contact-messages/{id}/read', [AdminController::class, 'markMessageRead']);
    Route::delete('contact-messages/{id}', [AdminController::class, 'deleteMessage']);
    Route::get('contact-stats', [AdminController::class, 'contactStats']);
});
