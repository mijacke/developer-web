<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DeveloperMap\DeveloperMapSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeveloperMapWriteController extends Controller
{
    public function __construct(private readonly DeveloperMapSyncService $syncService)
    {
    }

    public function set(Request $request): JsonResponse
    {
        $key = $request->input('key');
        $value = $request->input('value');

        if (!$key) {
            return response()->json(['message' => 'Key is required'], 400);
        }

        $this->syncService->set((string) $key, $value);

        return response()->json(['success' => true, 'key' => $key]);
    }

    public function remove(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Use specific delete endpoints instead',
        ]);
    }

    public function migrate(Request $request): JsonResponse
    {
        return response()->json([
            'migrated' => [],
            'skipped' => [],
            'message' => 'Migration not needed - using structured tables',
        ]);
    }
}
