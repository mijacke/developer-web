<?php

namespace App\Services;

use App\Models\Locality;
use App\Models\Project;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LocalityCrudService
{
    public function list(Project $project)
    {
        return $project->localities()
            ->orderBy('sort_order')
            ->get();
    }

    public function create(Project $project, array $validated): Locality
    {
        $dmId = trim((string) ($validated['dm_id'] ?? ''));
        $validated['project_id'] = $project->id;
        $validated['sort_order'] = $validated['sort_order'] ?? $project->localities()->count();

        if ($dmId !== '') {
            $existing = Locality::where('dm_id', $dmId)->first();
            if ($existing) {
                $existing->update($validated);
                if (!$existing->dm_id) {
                    $existing->update(['dm_id' => 'floor-' . $existing->id]);
                }
                return $existing->fresh();
            }
        }

        $locality = Locality::create($validated);
        if (!$locality->dm_id) {
            $locality->update(['dm_id' => 'floor-' . $locality->id]);
        }

        return $locality->fresh();
    }

    public function show(Project $project, Locality $locality): Locality
    {
        $this->assertOwnership($project, $locality);

        return $locality;
    }

    public function update(Project $project, Locality $locality, array $validated): Locality
    {
        $this->assertOwnership($project, $locality);

        $locality->update($validated);
        if (!$locality->dm_id) {
            $locality->update(['dm_id' => 'floor-' . $locality->id]);
        }

        return $locality->fresh();
    }

    public function delete(Project $project, Locality $locality): void
    {
        $this->assertOwnership($project, $locality);

        $locality->delete();
    }

    public function bulkUpdate(Project $project, array $localities): void
    {
        foreach ($localities as $localityData) {
            $locality = Locality::find($localityData['id']);
            if ($locality && $locality->project_id === $project->id) {
                $locality->update(array_filter($localityData, fn ($key) => $key !== 'id', ARRAY_FILTER_USE_KEY));
            }
        }
    }

    private function assertOwnership(Project $project, Locality $locality): void
    {
        if ($locality->project_id !== $project->id) {
            throw new NotFoundHttpException('Lokalita nepatrí k tomuto projektu');
        }
    }
}
