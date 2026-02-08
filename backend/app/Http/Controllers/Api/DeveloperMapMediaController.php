<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DeveloperMap\DeveloperMapMediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use RuntimeException;

class DeveloperMapMediaController extends Controller
{
    public function __construct(private readonly DeveloperMapMediaService $mediaService)
    {
    }

    public function saveImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'entity_id' => 'required|string',
            'attachment_id' => 'required',
        ]);

        $entityId = $validated['entity_id'];
        $attachmentId = (string) $validated['attachment_id'];

        $this->mediaService->saveImageReference($entityId, $attachmentId);

        return response()->json([
            'success' => true,
            'entity_id' => $entityId,
        ]);
    }

    public function uploadImage(Request $request): JsonResponse
    {
        if (!$request->hasFile('image')) {
            return response()->json([
                'success' => false,
                'error' => 'no_file',
                'message' => 'Nebol nahratý žiadny súbor.',
            ], 400);
        }

        $file = $request->file('image');
        if (!$file) {
            return response()->json([
                'success' => false,
                'error' => 'no_file',
                'message' => 'Nebol nahratý žiadny súbor.',
            ], 400);
        }

        try {
            $upload = $this->mediaService->uploadImage($file);
        } catch (InvalidArgumentException $exception) {
            $error = str_contains($exception->getMessage(), 'príliš veľký') ? 'file_too_large' : 'invalid_type';

            return response()->json([
                'success' => false,
                'error' => $error,
                'message' => $exception->getMessage(),
            ], 400);
        } catch (RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'error' => 'upload_failed',
                'message' => $exception->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'url' => $upload['url'],
            'filename' => $upload['filename'],
        ]);
    }
}
