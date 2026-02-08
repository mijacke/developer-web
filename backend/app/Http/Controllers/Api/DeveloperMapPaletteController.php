<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\MapPaletteRepository;
use Illuminate\Http\JsonResponse;

class DeveloperMapPaletteController extends Controller
{
    public function __construct(private readonly MapPaletteRepository $paletteRepository)
    {
    }

    public function deleteType(string $id): JsonResponse
    {
        $typeId = str_replace('type-', '', $id);

        if (!is_numeric($typeId)) {
            return response()->json(['success' => false, 'message' => 'Invalid type ID'], 400);
        }

        $deleted = $this->paletteRepository->deleteTypeById((int) $typeId);

        return response()->json([
            'success' => $deleted > 0,
            'deleted_id' => $typeId,
        ]);
    }

    public function deleteStatus(string $id): JsonResponse
    {
        $statusId = str_replace('status-', '', $id);

        if (!is_numeric($statusId)) {
            return response()->json(['success' => false, 'message' => 'Invalid status ID'], 400);
        }

        $deleted = $this->paletteRepository->deleteStatusById((int) $statusId);

        return response()->json([
            'success' => $deleted > 0,
            'deleted_id' => $statusId,
        ]);
    }
}
