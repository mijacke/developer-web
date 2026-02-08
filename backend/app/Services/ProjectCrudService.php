<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProjectCrudService
{
    public function list(): Collection
    {
        return Project::with('localities')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn (Project $project) => $this->mapSummary($project));
    }

    public function create(array $validated): Project
    {
        $dmId = trim((string) ($validated['dm_id'] ?? ''));
        if ($dmId !== '') {
            $existing = Project::where('dm_id', $dmId)->first();
            if ($existing) {
                if (array_key_exists('map_key', $validated)) {
                    $mapKeyInput = trim((string) ($validated['map_key'] ?? ''));
                    if ($mapKeyInput === '') {
                        unset($validated['map_key']);
                    } else {
                        $validated['map_key'] = $this->ensureUniqueMapKey(Str::slug($mapKeyInput), $existing->id);
                    }
                }
                $existing->update($validated);
                if (!$existing->map_key) {
                    $fallbackBase = Str::slug((string) ($existing->name ?? 'project'));
                    $existing->update([
                        'map_key' => $this->ensureUniqueMapKey($fallbackBase !== '' ? $fallbackBase : ('project-' . Str::random(6)), $existing->id),
                    ]);
                }
                return $existing->fresh();
            }
        }

        $mapKeyInput = trim((string) ($validated['map_key'] ?? ''));
        $baseMapKey = $mapKeyInput !== '' ? Str::slug($mapKeyInput) : Str::slug((string) ($validated['name'] ?? 'project'));
        $validated['map_key'] = $this->ensureUniqueMapKey($baseMapKey !== '' ? $baseMapKey : ('project-' . Str::random(6)));

        $project = Project::create($validated);
        if (!$project->dm_id) {
            $project->update(['dm_id' => 'project-' . $project->id]);
        }

        return $project->fresh();
    }

    public function show(Project $project): array
    {
        $project->load('localities');

        return $this->mapDetail($project);
    }

    public function showByMapKey(string $mapKey): array
    {
        $project = Project::with('localities')
            ->where('map_key', $mapKey)
            ->firstOrFail();

        return $this->mapDetail($project);
    }

    public function update(Project $project, array $validated): Project
    {
        if (array_key_exists('map_key', $validated)) {
            $mapKeyInput = trim((string) ($validated['map_key'] ?? ''));
            if ($mapKeyInput === '') {
                unset($validated['map_key']);
            } else {
                $validated['map_key'] = $this->ensureUniqueMapKey(Str::slug($mapKeyInput), $project->id);
            }
        }

        $project->update($validated);
        if (!$project->dm_id) {
            $project->update(['dm_id' => 'project-' . $project->id]);
        }

        return $project->fresh();
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }

    private function mapSummary(Project $project): array
    {
        return [
            'id' => $project->id,
            'dm_id' => $project->dm_id,
            'parent_id' => $project->parent_id,
            'name' => $project->name,
            'type' => $project->type,
            'image' => $project->image,
            'map_key' => $project->map_key,
            'sort_order' => $project->sort_order,
            'regions' => $project->regions,
            'frontend' => $project->frontend,
            'localities_count' => $project->localities->count(),
            'available_count' => $project->localities->where('status', 'available')->count(),
            'floors' => $project->localities->map(fn ($locality) => [
                'id' => $locality->id,
                'dm_id' => $locality->dm_id,
                'name' => $locality->name,
                'type' => $locality->type,
                'status' => $locality->status,
                'statusLabel' => $locality->status_label,
                'statusColor' => $locality->status_color,
                'area' => $locality->area,
                'price' => $locality->price,
                'rent' => $locality->rent,
                'floor' => $locality->floor,
                'image' => $locality->image,
                'svgPath' => $locality->svg_path,
                'regions' => $locality->regions,
                'metadata' => $locality->metadata,
            ]),
        ];
    }

    private function mapDetail(Project $project): array
    {
        return [
            'id' => $project->id,
            'dm_id' => $project->dm_id,
            'parent_id' => $project->parent_id,
            'name' => $project->name,
            'type' => $project->type,
            'image' => $project->image,
            'map_key' => $project->map_key,
            'sort_order' => $project->sort_order,
            'regions' => $project->regions,
            'frontend' => $project->frontend,
            'floors' => $project->localities->map(fn ($locality) => [
                'id' => $locality->id,
                'dm_id' => $locality->dm_id,
                'name' => $locality->name,
                'type' => $locality->type,
                'status' => $locality->status,
                'statusLabel' => $locality->status_label,
                'statusColor' => $locality->status_color,
                'area' => $locality->area,
                'price' => $locality->price,
                'rent' => $locality->rent,
                'floor' => $locality->floor,
                'image' => $locality->image,
                'svgPath' => $locality->svg_path,
                'regions' => $locality->regions,
                'metadata' => $locality->metadata,
            ]),
        ];
    }

    private function ensureUniqueMapKey(string $base, ?int $excludeId = null): string
    {
        $base = trim($base);
        if ($base === '') {
            $base = 'project-' . Str::random(6);
        }

        $candidate = $base;
        $suffix = 1;

        while (true) {
            $query = Project::where('map_key', $candidate);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $candidate = $base . '-' . $suffix;
            $suffix++;
        }

        return $candidate;
    }
}
