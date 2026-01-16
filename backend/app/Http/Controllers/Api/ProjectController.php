<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects
     */
    public function index(): JsonResponse
    {
        $projects = Project::with('localities')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'description' => $project->description,
                    'image' => $project->image,
                    'map_key' => $project->map_key,
                    'settings' => $project->settings,
                    'is_active' => $project->is_active,
                    'localities_count' => $project->localities->count(),
                    'available_count' => $project->localities->where('status', 'available')->count(),
                    'floors' => $project->localities->map(fn($l) => [
                        'id' => $l->id,
                        'name' => $l->name,
                        'type' => $l->type,
                        'status' => $l->status,
                        'statusLabel' => $l->status_label,
                        'statusColor' => $l->status_color,
                        'area' => $l->area,
                        'price' => $l->price,
                        'rent' => $l->rent,
                        'floor' => $l->floor,
                        'image' => $l->image,
                        'svgPath' => $l->svg_path,
                    ]),
                ];
            });

        return response()->json($projects);
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'settings' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['map_key'] = Str::slug($validated['name']) . '-' . Str::random(6);

        $project = Project::create($validated);

        return response()->json([
            'message' => 'Projekt bol úspešne vytvorený',
            'project' => $project,
        ], 201);
    }

    /**
     * Display the specified project
     */
    public function show(Project $project): JsonResponse
    {
        $project->load('localities');

        return response()->json([
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description,
            'image' => $project->image,
            'map_key' => $project->map_key,
            'settings' => $project->settings,
            'is_active' => $project->is_active,
            'floors' => $project->localities->map(fn($l) => [
                'id' => $l->id,
                'name' => $l->name,
                'type' => $l->type,
                'status' => $l->status,
                'statusLabel' => $l->status_label,
                'statusColor' => $l->status_color,
                'area' => $l->area,
                'price' => $l->price,
                'rent' => $l->rent,
                'floor' => $l->floor,
                'image' => $l->image,
                'svgPath' => $l->svg_path,
                'metadata' => $l->metadata,
            ]),
        ]);
    }

    /**
     * Display project by map_key (for frontend)
     */
    public function showByMapKey(string $mapKey): JsonResponse
    {
        $project = Project::with('localities')
            ->where('map_key', $mapKey)
            ->where('is_active', true)
            ->firstOrFail();

        return $this->show($project);
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'settings' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $project->update($validated);

        return response()->json([
            'message' => 'Projekt bol úspešne aktualizovaný',
            'project' => $project->fresh(),
        ]);
    }

    /**
     * Remove the specified project
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json([
            'message' => 'Projekt bol úspešne odstránený',
        ]);
    }
}
