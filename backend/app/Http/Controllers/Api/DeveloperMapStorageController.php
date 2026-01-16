<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeveloperMapSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Storage controller for Developer Map dashboard.
 * Stores all data in database (developer_map_settings table).
 */
class DeveloperMapStorageController extends Controller
{
    /**
     * List all stored keys
     */
    public function list(): JsonResponse
    {
        return response()->json(DeveloperMapSetting::getAllAsArray());
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

        $value = DeveloperMapSetting::getValue($key);

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

        DeveloperMapSetting::setValue($validated['key'], $validated['value']);

        return response()->json([
            'success' => true,
            'key' => $validated['key'],
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

        DeveloperMapSetting::removeValue($key);

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

        foreach ($payload as $key => $value) {
            // Skip if already exists
            $existing = DeveloperMapSetting::where('key', $key)->first();
            if ($existing) {
                $skipped[$key] = 'already exists';
                continue;
            }

            DeveloperMapSetting::setValue($key, $value);
            $migrated[$key] = true;
        }

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
        $images = DeveloperMapSetting::getValue($imagesKey, []);
        
        if (!is_array($images)) {
            $images = [];
        }
        
        $images[$validated['entity_id']] = [
            'id' => $validated['attachment_id'],
            'url' => $validated['attachment_id'],
            'alt' => '',
            'entity_id' => $validated['entity_id'],
            'key' => $validated['key'],
        ];

        DeveloperMapSetting::setValue($imagesKey, $images);

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
            $value = DeveloperMapSetting::getValue($key);
            if ($value !== null) {
                $data[$key] = $value;
            }
        }

        return response()->json($data);
    }
}
