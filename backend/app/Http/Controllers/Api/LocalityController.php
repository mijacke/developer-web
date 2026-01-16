<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Locality;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function store(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'status' => 'required|string|max:50',
            'status_label' => 'nullable|string|max:100',
            'status_color' => 'nullable|string|max:20',
            'area' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'rent' => 'nullable|numeric|min:0',
            'floor' => 'nullable|string|max:50',
            'image' => 'nullable|string',
            'svg_path' => 'nullable|string',
            'metadata' => 'nullable|array',
            'sort_order' => 'nullable|integer',
        ]);

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
    public function update(Request $request, Project $project, Locality $locality): JsonResponse
    {
        if ($locality->project_id !== $project->id) {
            return response()->json(['message' => 'Lokalita nepatrí k tomuto projektu'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'nullable|string|max:100',
            'status' => 'sometimes|required|string|max:50',
            'status_label' => 'nullable|string|max:100',
            'status_color' => 'nullable|string|max:20',
            'area' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'rent' => 'nullable|numeric|min:0',
            'floor' => 'nullable|string|max:50',
            'image' => 'nullable|string',
            'svg_path' => 'nullable|string',
            'metadata' => 'nullable|array',
            'sort_order' => 'nullable|integer',
        ]);

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
    public function bulkUpdate(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'localities' => 'required|array',
            'localities.*.id' => 'required|exists:localities,id',
            'localities.*.sort_order' => 'nullable|integer',
            'localities.*.status' => 'nullable|string|max:50',
        ]);

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
