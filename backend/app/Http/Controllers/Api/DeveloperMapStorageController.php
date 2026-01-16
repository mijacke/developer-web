<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Storage controller for Developer Map dashboard.
 * Provides key-value storage compatible with the original dm.js storage client.
 */
class DeveloperMapStorageController extends Controller
{
    private const CACHE_PREFIX = 'dm_storage_';
    private const CACHE_TTL = 60 * 60 * 24 * 365; // 1 year

    /**
     * List all stored keys
     */
    public function list(): JsonResponse
    {
        $keys = Cache::get(self::CACHE_PREFIX . 'keys', []);
        $data = [];
        
        foreach ($keys as $key) {
            $value = Cache::get(self::CACHE_PREFIX . $key);
            if ($value !== null) {
                $data[$key] = $value;
            }
        }

        return response()->json($data);
    }

    /**
     * Get a value by key
     */
    public function get(Request $request): JsonResponse
    {
        $key = $request->query('key');
        
        if (!$key) {
            return response()->json(['message' => 'Key is required'], 400);
        }

        $value = Cache::get(self::CACHE_PREFIX . $key);

        return response()->json([
            'key' => $key,
            'value' => $value,
        ]);
    }

    /**
     * Set a value
     */
    public function set(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'value' => 'nullable',
        ]);

        $key = $validated['key'];
        $value = $validated['value'];

        // Store the value
        Cache::put(self::CACHE_PREFIX . $key, $value, self::CACHE_TTL);

        // Track the key
        $keys = Cache::get(self::CACHE_PREFIX . 'keys', []);
        if (!in_array($key, $keys)) {
            $keys[] = $key;
            Cache::put(self::CACHE_PREFIX . 'keys', $keys, self::CACHE_TTL);
        }

        return response()->json([
            'success' => true,
            'key' => $key,
        ]);
    }

    /**
     * Remove a value
     */
    public function remove(Request $request): JsonResponse
    {
        $key = $request->input('key');
        
        if (!$key) {
            return response()->json(['message' => 'Key is required'], 400);
        }

        Cache::forget(self::CACHE_PREFIX . $key);

        // Remove from tracked keys
        $keys = Cache::get(self::CACHE_PREFIX . 'keys', []);
        $keys = array_filter($keys, fn($k) => $k !== $key);
        Cache::put(self::CACHE_PREFIX . 'keys', array_values($keys), self::CACHE_TTL);

        return response()->json([
            'success' => true,
            'key' => $key,
        ]);
    }

    /**
     * Migrate data from payload
     */
    public function migrate(Request $request): JsonResponse
    {
        $payload = $request->input('payload', []);
        $migrated = [];
        $skipped = [];

        if (!is_array($payload)) {
            return response()->json([
                'migrated' => $migrated,
                'skipped' => $skipped,
            ]);
        }

        $keys = Cache::get(self::CACHE_PREFIX . 'keys', []);

        foreach ($payload as $key => $value) {
            // Skip if already exists
            if (Cache::has(self::CACHE_PREFIX . $key)) {
                $skipped[$key] = 'already exists';
                continue;
            }

            Cache::put(self::CACHE_PREFIX . $key, $value, self::CACHE_TTL);
            
            if (!in_array($key, $keys)) {
                $keys[] = $key;
            }
            
            $migrated[$key] = true;
        }

        Cache::put(self::CACHE_PREFIX . 'keys', $keys, self::CACHE_TTL);

        return response()->json([
            'migrated' => $migrated,
            'skipped' => $skipped,
        ]);
    }

    /**
     * Save image reference
     */
    public function saveImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'entity_id' => 'required|string',
            'attachment_id' => 'required',
        ]);

        $imagesKey = 'dm-images';
        $images = Cache::get(self::CACHE_PREFIX . $imagesKey, []);
        
        $images[$validated['entity_id']] = [
            'id' => $validated['attachment_id'],
            'url' => $validated['attachment_id'], // In our case, this is the URL
            'alt' => '',
            'entity_id' => $validated['entity_id'],
            'key' => $validated['key'],
        ];

        Cache::put(self::CACHE_PREFIX . $imagesKey, $images, self::CACHE_TTL);

        // Track key
        $keys = Cache::get(self::CACHE_PREFIX . 'keys', []);
        if (!in_array($imagesKey, $keys)) {
            $keys[] = $imagesKey;
            Cache::put(self::CACHE_PREFIX . 'keys', $keys, self::CACHE_TTL);
        }

        return response()->json([
            'success' => true,
            'image' => $images[$validated['entity_id']],
        ]);
    }

    /**
     * Get bootstrap data for the app
     */
    public function bootstrap(): JsonResponse
    {
        $keys = ['dm-projects', 'dm-colors', 'dm-types', 'dm-statuses', 'dm-images', 'dm-fonts', 'dm-selected-font', 'dm-frontend-accent-color'];
        $data = [];

        foreach ($keys as $key) {
            $value = Cache::get(self::CACHE_PREFIX . $key);
            if ($value !== null) {
                $data[$key] = $value;
            }
        }

        return response()->json($data);
    }
}
