<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Locality\BulkUpdateLocalitiesRequest;
use App\Http\Requests\Locality\StoreLocalityRequest;
use App\Http\Requests\Locality\UpdateLocalityRequest;
use App\Models\Locality;
use App\Models\Project;
use App\Services\LocalityCrudService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LocalityController extends Controller
{
    public function __construct(private readonly LocalityCrudService $localityCrudService)
    {
    }

    public function index(Project $project): JsonResponse
    {
        return response()->json($this->localityCrudService->list($project));
    }

    public function store(StoreLocalityRequest $request, Project $project): JsonResponse
    {
        $locality = $this->localityCrudService->create($project, $request->validated());

        return response()->json([
            'message' => 'Lokalita bola úspešne vytvorená',
            'locality' => $locality,
        ], 201);
    }

    public function show(Project $project, Locality $locality): JsonResponse
    {
        try {
            $result = $this->localityCrudService->show($project, $locality);
        } catch (NotFoundHttpException $exception) {
            return response()->json(['message' => $exception->getMessage()], 404);
        }

        return response()->json($result);
    }

    public function update(UpdateLocalityRequest $request, Project $project, Locality $locality): JsonResponse
    {
        try {
            $updated = $this->localityCrudService->update($project, $locality, $request->validated());
        } catch (NotFoundHttpException $exception) {
            return response()->json(['message' => $exception->getMessage()], 404);
        }

        return response()->json([
            'message' => 'Lokalita bola úspešne aktualizovaná',
            'locality' => $updated,
        ]);
    }

    public function destroy(Project $project, Locality $locality): JsonResponse
    {
        try {
            $this->localityCrudService->delete($project, $locality);
        } catch (NotFoundHttpException $exception) {
            return response()->json(['message' => $exception->getMessage()], 404);
        }

        return response()->json([
            'message' => 'Lokalita bola úspešne odstránená',
        ]);
    }

    public function bulkUpdate(BulkUpdateLocalitiesRequest $request, Project $project): JsonResponse
    {
        $this->localityCrudService->bulkUpdate($project, $request->validated()['localities']);

        return response()->json([
            'message' => 'Lokality boli úspešne aktualizované',
        ]);
    }
}
