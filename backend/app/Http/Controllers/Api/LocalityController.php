<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Locality\BulkUpdateLocalitiesRequest;
use App\Http\Requests\Locality\StoreLocalityRequest;
use App\Http\Requests\Locality\UpdateLocalityRequest;
use App\Models\Locality;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class LocalityController extends Controller
{
    /**
     * Display a listing of localities for a project
     */
    public function index(Project $project): JsonResponse
    {
        $localities = $project->localities()
            ->orderBy('sort_order')
            ->get();

        return response()->json($localities);
    }

    /**
     * Store a newly created locality
     */
    public function store(StoreLocalityRequest $request, Project $project): JsonResponse
    {
        $validated = $request->validated();

        $validated['project_id'] = $project->id;
        $validated['sort_order'] = $validated['sort_order'] ?? $project->localities()->count();

        $locality = Locality::create($validated);

        return response()->json([
            'message' => 'Lokalita bola úspešne vytvorená',
            'locality' => $locality,
        ], 201);
    }

    /**
     * Display the specified locality
     */
    public function show(Project $project, Locality $locality): JsonResponse
    {
        if ($locality->project_id !== $project->id) {
            return response()->json(['message' => 'Lokalita nepatrí k tomuto projektu'], 404);
        }

        return response()->json($locality);
    }

    /**
     * Update the specified locality
     */
    public function update(UpdateLocalityRequest $request, Project $project, Locality $locality): JsonResponse
    {
        if ($locality->project_id !== $project->id) {
            return response()->json(['message' => 'Lokalita nepatrí k tomuto projektu'], 404);
        }

        $validated = $request->validated();

        $locality->update($validated);

        return response()->json([
            'message' => 'Lokalita bola úspešne aktualizovaná',
            'locality' => $locality->fresh(),
        ]);
    }

    /**
     * Remove the specified locality
     */
    public function destroy(Project $project, Locality $locality): JsonResponse
    {
        if ($locality->project_id !== $project->id) {
            return response()->json(['message' => 'Lokalita nepatrí k tomuto projektu'], 404);
        }

        $locality->delete();

        return response()->json([
            'message' => 'Lokalita bola úspešne odstránená',
        ]);
    }

    /**
     * Bulk update localities (for sorting, status changes, etc.)
     */
    public function bulkUpdate(BulkUpdateLocalitiesRequest $request, Project $project): JsonResponse
    {
        $validated = $request->validated();

        foreach ($validated['localities'] as $localityData) {
            $locality = Locality::find($localityData['id']);
            if ($locality && $locality->project_id === $project->id) {
                $locality->update(array_filter($localityData, fn($key) => $key !== 'id', ARRAY_FILTER_USE_KEY));
            }
        }

        return response()->json([
            'message' => 'Lokality boli úspešne aktualizované',
        ]);
    }
}
