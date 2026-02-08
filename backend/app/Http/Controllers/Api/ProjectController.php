<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ProjectCrudService;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectCrudService $projectCrudService)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->projectCrudService->list());
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->projectCrudService->create($request->validated());

        return response()->json([
            'message' => 'Projekt bol úspešne vytvorený',
            'project' => $project,
        ], 201);
    }

    public function show(Project $project): JsonResponse
    {
        return response()->json($this->projectCrudService->show($project));
    }

    public function showByMapKey(string $mapKey): JsonResponse
    {
        return response()->json($this->projectCrudService->showByMapKey($mapKey));
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $updated = $this->projectCrudService->update($project, $request->validated());

        return response()->json([
            'message' => 'Projekt bol úspešne aktualizovaný',
            'project' => $updated,
        ]);
    }

    public function destroy(Project $project): JsonResponse
    {
        $this->projectCrudService->delete($project);

        return response()->json([
            'message' => 'Projekt bol úspešne odstránený',
        ]);
    }
}
