<?php

namespace App\Services\DeveloperMap;

use App\Models\Project;
use App\Repositories\MapPaletteRepository;
use App\Services\FrontendColorService;

class DeveloperMapReadService
{
    public function __construct(
        private readonly MapPaletteRepository $paletteRepository,
        private readonly FrontendColorService $frontendColorService,
    ) {
    }

    public function list(): array
    {
        return [
            'dm-projects' => Project::with('localities')->orderBy('sort_order')->get()->map(function ($project) {
                return [
                    'id' => $project->dm_id ?: ('project-' . $project->id),
                    'dbId' => $project->id,
                    'parentId' => $project->parent?->dm_id ?: ($project->parent_id ? 'project-' . $project->parent_id : null),
                    'name' => $project->name,
                    'type' => $project->type,
                    'badge' => strtoupper(substr($project->name, 0, 2)),
                    'publicKey' => $project->map_key,
                    'image' => $this->getImageUrl($project->image),
                    'imageUrl' => $this->getImageUrl($project->image),
                    'regions' => is_array($project->regions) ? $project->regions : [],
                    'frontend' => is_array($project->frontend) ? $project->frontend : null,
                    'floors' => $project->localities->map(function ($locality) {
                        $meta = is_array($locality->metadata) ? $locality->metadata : [];
                        return [
                            'id' => $locality->dm_id ?: ('floor-' . $locality->id),
                            'dbId' => $locality->id,
                            'name' => $locality->name,
                            'label' => $locality->name,
                            'designation' => $meta['designation'] ?? null,
                            'prefix' => $meta['prefix'] ?? null,
                            'suffix' => $meta['suffix'] ?? null,
                            'url' => $meta['url'] ?? null,
                            'detailUrl' => $meta['detailUrl'] ?? null,
                            'statusId' => $meta['statusId'] ?? null,
                            'type' => $locality->type,
                            'status' => $locality->status,
                            'statusLabel' => $locality->status,
                            'area' => $locality->area,
                            'price' => $locality->price,
                            'rent' => $locality->rent,
                            'floor' => $locality->floor,
                            'image' => $this->getImageUrl($locality->image),
                            'imageUrl' => $this->getImageUrl($locality->image),
                            'svgPath' => $locality->svg_path,
                            'regions' => is_array($locality->regions) ? $locality->regions : [],
                        ];
                    })->toArray(),
                ];
            })->toArray(),
            'dm-statuses' => $this->paletteRepository->getStatuses()->map(fn ($status) => [
                'id' => 'status-' . $status->id,
                'name' => $status->name,
                'label' => $status->label,
                'color' => $status->color,
                'isDefault' => $status->is_default,
            ])->toArray(),
            'dm-types' => $this->paletteRepository->getTypes()->map(fn ($type) => [
                'id' => 'type-' . $type->id,
                'name' => $type->name,
                'label' => $type->label,
                'color' => $type->color,
            ])->toArray(),
            'dm-colors' => $this->paletteRepository->getColors()->map(fn ($color) => [
                'id' => 'color-' . $color->id,
                'name' => $color->name,
                'label' => $color->label,
                'value' => $color->value,
            ])->toArray(),
            'dm-frontend-colors' => $this->frontendColorService->all()->map(fn ($color) => [
                'id' => 'frontend-color-' . $color->id,
                'name' => $color->name,
                'value' => $color->value,
                'isActive' => (bool) $color->is_active,
            ])->toArray(),
            'dm-frontend-accent-color' => $this->frontendColorService->getAccentColor('#6366F1'),
        ];
    }

    public function get(string $key): array
    {
        if ($key === 'dm-projects' && Project::count() === 0) {
            return ['value' => []];
        }

        return $this->list();
    }

    private function getImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return url($path);
    }
}
