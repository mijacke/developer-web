<?php

namespace App\Services\DeveloperMap;

use App\Models\Locality;
use App\Models\Project;
use App\Repositories\MapPaletteRepository;
use App\Services\FrontendColorService;
use Illuminate\Support\Str;

class DeveloperMapSyncService
{
    public function __construct(
        private readonly MapPaletteRepository $paletteRepository,
        private readonly FrontendColorService $frontendColorService,
    ) {
    }

    public function set(string $key, mixed $value): void
    {
        switch ($key) {
            case 'dm-projects':
                $this->syncProjects(is_array($value) ? $value : []);
                break;
            case 'dm-statuses':
                $this->syncStatuses(is_array($value) ? $value : []);
                break;
            case 'dm-types':
                $this->syncTypes(is_array($value) ? $value : []);
                break;
            case 'dm-colors':
                $this->syncMapColors(is_array($value) ? $value : []);
                break;
            case 'dm-frontend-colors':
                $this->syncFrontendColors(is_array($value) ? $value : []);
                break;
            case 'dm-frontend-accent-color':
                if (is_string($value)) {
                    $raw = trim($value);
                    $prefixed = str_starts_with($raw, '#') ? $raw : ('#' . $raw);
                    $normalised = strtoupper($prefixed);
                    if (preg_match('/^#[0-9A-F]{6}$/', $normalised)) {
                        $this->updateFrontendAccentColor($normalised);
                    }
                }
                break;
            default:
                break;
        }
    }

    private function normaliseProjectFrontend(mixed $frontend, mixed $fallback = null): ?array
    {
        $candidate = is_array($frontend) ? $frontend : (is_array($fallback) ? $fallback : null);
        if (!$candidate) {
            return null;
        }

        $table = $candidate['locationTable'] ?? $candidate['location_table'] ?? null;
        if (!is_array($table)) {
            return null;
        }

        $scopeRaw = strtolower(trim((string) ($table['scope'] ?? 'current')));
        $scope = $scopeRaw === 'hierarchy' ? 'hierarchy' : 'current';

        $tableOnly = $table['tableonly'] ?? $table['tableOnly'] ?? $table['table_only'] ?? false;

        return [
            'locationTable' => [
                'enabled' => (bool) ($table['enabled'] ?? false),
                'scope' => $scope,
                'tableonly' => (bool) $tableOnly,
            ],
        ];
    }

    private function normaliseNullableDecimal(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if (!is_string($value)) {
            return null;
        }

        $raw = trim($value);
        if ($raw === '') {
            return null;
        }

        $clean = preg_replace('/[^0-9,\.\-]/', '', $raw) ?? '';
        $clean = trim($clean);
        if ($clean === '' || $clean === '-' || $clean === '.' || $clean === ',') {
            return null;
        }

        $lastComma = strrpos($clean, ',');
        $lastDot = strrpos($clean, '.');

        if ($lastComma !== false && $lastDot !== false) {
            if ($lastComma > $lastDot) {
                $clean = str_replace('.', '', $clean);
                $clean = str_replace(',', '.', $clean);
            } else {
                $clean = str_replace(',', '', $clean);
            }
        } elseif ($lastComma !== false) {
            $clean = str_replace(',', '.', $clean);
        }

        if (substr_count($clean, '.') > 1) {
            $parts = explode('.', $clean);
            $last = array_pop($parts);
            $clean = implode('', $parts) . '.' . $last;
        }

        $clean = trim($clean);
        if (!preg_match('/^-?\d+(?:\.\d+)?$/', $clean)) {
            return null;
        }

        return $clean;
    }

    private function syncProjects(array $projects): void
    {
        $incomingProjectIds = [];

        $normaliseId = static function (?string $raw, string $prefix): ?string {
            $raw = is_string($raw) ? trim($raw) : '';
            if ($raw === '') {
                return null;
            }
            if (str_starts_with($raw, "new-{$prefix}-")) {
                return $prefix . '-' . substr($raw, strlen("new-{$prefix}-"));
            }
            if (str_starts_with($raw, "{$prefix}-")) {
                return $raw;
            }
            return $raw;
        };

        $findProjectForDmId = static function (string $dmId, ?string $mapKey): ?Project {
            $project = Project::where('dm_id', $dmId)->first();
            if ($project) {
                return $project;
            }

            if (preg_match('/^project-(\d+)$/', $dmId, $match)) {
                $byId = Project::find((int) $match[1]);
                if ($byId) {
                    return $byId;
                }
            }

            $mapKey = is_string($mapKey) ? trim($mapKey) : '';
            if ($mapKey !== '') {
                return Project::where('map_key', $mapKey)->first();
            }

            return null;
        };

        $normaliseSortLabel = static function (mixed $value): string {
            $label = is_string($value) ? trim($value) : '';
            if ($label === '') {
                return '';
            }
            $label = Str::ascii($label);
            $label = mb_strtolower($label, 'UTF-8');
            $label = preg_replace('/\s+/', ' ', $label) ?? $label;
            return trim($label);
        };

        $compareProjectRows = static function (mixed $a, mixed $b) use ($normaliseSortLabel): int {
            $aLabel = $normaliseSortLabel(is_array($a) ? ($a['name'] ?? $a['label'] ?? '') : ($a?->name ?? ''));
            $bLabel = $normaliseSortLabel(is_array($b) ? ($b['name'] ?? $b['label'] ?? '') : ($b?->name ?? ''));
            $cmp = strnatcmp($aLabel, $bLabel);
            if ($cmp !== 0) {
                return $cmp;
            }
            $aId = is_array($a) ? (string) ($a['id'] ?? '') : (string) ($a?->id ?? '');
            $bId = is_array($b) ? (string) ($b['id'] ?? '') : (string) ($b?->id ?? '');
            return strnatcmp($aId, $bId);
        };

        foreach ($projects as $index => $projectData) {
            $rawId = $projectData['id'] ?? null;
            $dmId = $normaliseId(is_string($rawId) ? $rawId : (string) $rawId, 'project');
            if (!$dmId) {
                continue;
            }

            $mapKey = $projectData['publicKey'] ?? $projectData['map_key'] ?? null;
            $existing = $findProjectForDmId($dmId, is_string($mapKey) ? $mapKey : null);
            $project = $existing;
            if (!$mapKey || (is_string($mapKey) && str_starts_with($mapKey, 'pk_'))) {
                $mapKey = $this->slugify($projectData['name'] ?? 'mapa');
                $mapKey = $this->ensureUniqueMapKey($mapKey, $existing?->id);
            }

            $data = [
                'dm_id' => $dmId,
                'name' => $projectData['name'] ?? 'Nový projekt',
                'type' => $projectData['type'] ?? null,
                'image' => $projectData['imageUrl'] ?? $projectData['image'] ?? null,
                'map_key' => $mapKey,
                'sort_order' => $index + 1,
                'regions' => is_array($projectData['regions'] ?? null) ? $projectData['regions'] : [],
                'frontend' => $this->normaliseProjectFrontend($projectData['frontend'] ?? null, $project?->frontend),
            ];

            if ($project) {
                $project->update($data);
                if (!$project->dm_id) {
                    $project->update(['dm_id' => $dmId]);
                }
            } else {
                $project = Project::create($data);
            }

            $incomingProjectIds[] = $project->id;

            $incomingFloorDmIds = [];
            $floors = is_array($projectData['floors'] ?? null) ? $projectData['floors'] : [];
            $floors = array_values(array_filter($floors, fn ($floor) => is_array($floor)));
            usort($floors, $compareProjectRows);

            foreach ($floors as $floorIndex => $floorData) {
                if (!is_array($floorData)) {
                    continue;
                }

                $floorRawId = $floorData['id'] ?? null;
                $floorDmId = $normaliseId(is_string($floorRawId) ? $floorRawId : (string) $floorRawId, 'floor');
                if (!$floorDmId) {
                    continue;
                }

                $incomingFloorDmIds[] = $floorDmId;

                $metadata = [
                    'designation' => $floorData['designation'] ?? null,
                    'prefix' => $floorData['prefix'] ?? null,
                    'suffix' => $floorData['suffix'] ?? null,
                    'url' => $floorData['url'] ?? null,
                    'detailUrl' => $floorData['detailUrl'] ?? null,
                    'statusId' => $floorData['statusId'] ?? null,
                ];

                $localityData = [
                    'dm_id' => $floorDmId,
                    'project_id' => $project->id,
                    'name' => $floorData['name'] ?? ($floorData['label'] ?? 'Lokalita'),
                    'type' => $floorData['type'] ?? null,
                    'status' => $floorData['status'] ?? 'available',
                    'status_label' => $floorData['statusLabel'] ?? null,
                    'status_color' => $floorData['statusColor'] ?? null,
                    'area' => $this->normaliseNullableDecimal($floorData['area'] ?? null),
                    'price' => $this->normaliseNullableDecimal($floorData['price'] ?? null),
                    'rent' => $this->normaliseNullableDecimal($floorData['rent'] ?? null),
                    'floor' => $floorData['floor'] ?? null,
                    'image' => $floorData['imageUrl'] ?? ($floorData['image'] ?? null),
                    'svg_path' => $floorData['svgPath'] ?? null,
                    'regions' => is_array($floorData['regions'] ?? null) ? $floorData['regions'] : [],
                    'metadata' => array_filter($metadata, fn ($item) => $item !== null && $item !== ''),
                    'sort_order' => $floorIndex + 1,
                ];

                $locality = Locality::where('dm_id', $floorDmId)->first();
                if (!$locality && preg_match('/^floor-(\d+)$/', $floorDmId, $match)) {
                    $locality = Locality::find((int) $match[1]);
                }

                if ($locality) {
                    $locality->update($localityData);
                    if (!$locality->dm_id) {
                        $locality->update(['dm_id' => $floorDmId]);
                    }
                } else {
                    Locality::create($localityData);
                }
            }

            if (!empty($incomingFloorDmIds)) {
                Locality::where('project_id', $project->id)
                    ->whereNotIn('dm_id', $incomingFloorDmIds)
                    ->delete();
            } else {
                Locality::where('project_id', $project->id)->delete();
            }
        }

        foreach ($projects as $projectData) {
            $rawId = $projectData['id'] ?? null;
            $dmId = $normaliseId(is_string($rawId) ? $rawId : (string) $rawId, 'project');
            if (!$dmId) {
                continue;
            }

            $project = Project::where('dm_id', $dmId)->first();
            if (!$project) {
                continue;
            }

            $parentValue = $projectData['parentId'] ?? $projectData['parent_id'] ?? null;
            $parentDmId = $normaliseId(is_string($parentValue) ? $parentValue : (string) $parentValue, 'project');
            $parent = $parentDmId ? Project::where('dm_id', $parentDmId)->first() : null;

            $project->update(['parent_id' => $parent?->id]);
        }

        if (!empty($incomingProjectIds)) {
            $projectsForOrdering = Project::whereIn('id', $incomingProjectIds)->get(['id', 'parent_id', 'name']);
            $projectById = $projectsForOrdering->keyBy('id');
            $childrenByParentId = [];
            $roots = [];

            foreach ($projectsForOrdering as $project) {
                $parentId = $project->parent_id;
                if ($parentId && $projectById->has($parentId)) {
                    $childrenByParentId[$parentId] ??= [];
                    $childrenByParentId[$parentId][] = $project;
                } else {
                    $roots[] = $project;
                }
            }

            usort($roots, $compareProjectRows);
            foreach ($childrenByParentId as $parentId => $children) {
                usort($children, $compareProjectRows);
                $childrenByParentId[$parentId] = $children;
            }

            $visited = [];
            $sorted = [];
            $walk = function (Project $project) use (&$walk, &$visited, &$sorted, $childrenByParentId): void {
                if (isset($visited[$project->id])) {
                    return;
                }
                $visited[$project->id] = true;
                $sorted[] = $project;
                foreach (($childrenByParentId[$project->id] ?? []) as $child) {
                    $walk($child);
                }
            };

            foreach ($roots as $root) {
                $walk($root);
            }

            $leftovers = $projectsForOrdering
                ->filter(fn (Project $project) => !isset($visited[$project->id]))
                ->values()
                ->all();
            usort($leftovers, $compareProjectRows);
            foreach ($leftovers as $project) {
                $walk($project);
            }

            foreach ($sorted as $order => $project) {
                $project->update(['sort_order' => $order + 1]);
            }
        }

        if (!empty($incomingProjectIds)) {
            Project::whereNotIn('id', $incomingProjectIds)->delete();
        }
    }

    private function syncStatuses(array $statuses): void
    {
        $this->paletteRepository->syncStatuses($statuses);
    }

    private function syncTypes(array $types): void
    {
        $this->paletteRepository->syncTypes($types);
    }

    private function syncMapColors(array $colors): void
    {
        $this->paletteRepository->syncColors($colors);
    }

    private function syncFrontendColors(array $colors): void
    {
        $this->frontendColorService->sync($colors);
    }

    private function updateFrontendAccentColor(string $colorValue): void
    {
        $this->frontendColorService->updateAccentColor($colorValue);
    }

    private function slugify(string $input): string
    {
        $transliteration = [
            'á' => 'a', 'ä' => 'a', 'č' => 'c', 'ď' => 'd', 'é' => 'e', 'ě' => 'e',
            'í' => 'i', 'ľ' => 'l', 'ĺ' => 'l', 'ň' => 'n', 'ó' => 'o', 'ô' => 'o',
            'ö' => 'o', 'ŕ' => 'r', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ú' => 'u',
            'ů' => 'u', 'ü' => 'u', 'ý' => 'y', 'ž' => 'z',
            'Á' => 'a', 'Ä' => 'a', 'Č' => 'c', 'Ď' => 'd', 'É' => 'e', 'Ě' => 'e',
            'Í' => 'i', 'Ľ' => 'l', 'Ĺ' => 'l', 'Ň' => 'n', 'Ó' => 'o', 'Ô' => 'o',
            'Ö' => 'o', 'Ŕ' => 'r', 'Ř' => 'r', 'Š' => 's', 'Ť' => 't', 'Ú' => 'u',
            'Ů' => 'u', 'Ü' => 'u', 'Ý' => 'y', 'Ž' => 'z',
        ];

        $slug = strtr($input, $transliteration);
        $slug = strtolower($slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        $slug = preg_replace('/-+/', '-', $slug);

        return $slug ?: 'mapa';
    }

    private function ensureUniqueMapKey(string $base, ?int $excludeId = null): string
    {
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
