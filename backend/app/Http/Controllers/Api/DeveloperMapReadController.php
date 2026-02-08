<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DeveloperMap\DeveloperMapReadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeveloperMapReadController extends Controller
{
    public function __construct(private readonly DeveloperMapReadService $readService)
    {
    }

    public function list(): JsonResponse
    {
        return response()->json($this->readService->list());
    }

    public function get(Request $request): JsonResponse
    {
        $key = $request->query('key');

        if (!$key) {
            return response()->json(['message' => 'Key is required'], 400);
        }

        return response()->json($this->readService->get((string) $key));
    }

    public function bootstrap(): JsonResponse
    {
        return response()->json($this->readService->list());
    }
}
