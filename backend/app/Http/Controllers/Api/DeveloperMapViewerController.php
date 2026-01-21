<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DmFrontendColor;
use App\Models\DmStatus;
use App\Models\Locality;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeveloperMapViewerController extends Controller
{
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

    private function tryGetImageSize(?string $imageUrl): ?array
    {
        if (!$imageUrl) {
            return null;
        }

        $path = parse_url($imageUrl, PHP_URL_PATH);
        if (!is_string($path) || $path === '') {
            return null;
        }

        $absolute = public_path(ltrim($path, '/'));
        if (!is_file($absolute)) {
            return null;
        }

        $size = @getimagesize($absolute);
        if (!is_array($size) || empty($size[0]) || empty($size[1])) {
            return null;
        }

        return [
            'width' => (int) $size[0],
            'height' => (int) $size[1],
        ];
    }

    private function resolveStatusId(Locality $locality, array $statuses): ?string
    {
        $raw = trim((string) ($locality->metadata['statusId'] ?? $locality->metadata['status_id'] ?? $locality->status ?? ''));
        if ($raw === '') {
            return null;
        }

        if (str_starts_with($raw, 'status-')) {
            return $raw;
        }

        if (ctype_digit($raw)) {
            return 'status-' . $raw;
        }

        $needle = mb_strtolower($raw);
        foreach ($statuses as $status) {
            $name = mb_strtolower((string) ($status['label'] ?? $status['name'] ?? ''));
            $key = mb_strtolower((string) ($status['key'] ?? ''));
            if ($name === $needle || $key === $needle) {
                return (string) ($status['id'] ?? null);
            }
        }

        return null;
    }

    private function extractNormalisedPoints(Locality $locality, int $width, int $height): array
    {
        $metadata = is_array($locality->metadata) ? $locality->metadata : [];

        $candidates = [
            $metadata['geometry']['points'] ?? null,
            $metadata['points'] ?? null,
            $metadata['polygon'] ?? null,
        ];

        foreach ($candidates as $candidate) {
            if (is_array($candidate) && count($candidate) >= 3) {
                $points = [];
                foreach ($candidate as $pair) {
                    if (!is_array($pair) || count($pair) < 2) {
                        continue;
                    }
                    $x = (float) $pair[0];
                    $y = (float) $pair[1];
                    $points[] = [round(max(0.0, min(1.0, $x)), 4), round(max(0.0, min(1.0, $y)), 4)];
                }
                if (count($points) >= 3) {
                    return $points;
                }
            }
        }

        $rawSvg = trim((string) ($locality->svg_path ?? ''));
        if ($rawSvg === '') {
            return [];
        }

        // Allow JSON-encoded points in svg_path as a fallback.
        if (str_starts_with($rawSvg, '[')) {
            $decoded = json_decode($rawSvg, true);
            if (is_array($decoded) && count($decoded) >= 3) {
                $points = [];
                foreach ($decoded as $pair) {
                    if (!is_array($pair) || count($pair) < 2) {
                        continue;
                    }
                    $x = (float) $pair[0];
                    $y = (float) $pair[1];
                    $points[] = [round(max(0.0, min(1.0, $x)), 4), round(max(0.0, min(1.0, $y)), 4)];
                }
                if (count($points) >= 3) {
                    return $points;
                }
            }
        }

        // Parse SVG path "M x y L x y ... Z" (pixel coords) into points.
        preg_match_all('/-?\\d+(?:\\.\\d+)?/', $rawSvg, $matches);
        $numbers = $matches[0] ?? [];
        if (count($numbers) < 6) {
            return [];
        }

        $coords = array_map('floatval', $numbers);
        if (count($coords) % 2 === 1) {
            array_pop($coords);
        }

        $max = max($coords);
        $alreadyNormalised = $max <= 1.5;

        $points = [];
        for ($i = 0; $i < count($coords); $i += 2) {
            $x = $coords[$i];
            $y = $coords[$i + 1];
            if (!$alreadyNormalised) {
                $x = $width > 0 ? $x / $width : 0;
                $y = $height > 0 ? $y / $height : 0;
            }
            $points[] = [round(max(0.0, min(1.0, $x)), 4), round(max(0.0, min(1.0, $y)), 4)];
        }

        return count($points) >= 3 ? $points : [];
    }

    private function parseChildProjectDmId(mixed $value): ?string
    {
        if (is_array($value)) {
            $type = strtolower(trim((string) ($value['type'] ?? $value['kind'] ?? $value['nodeType'] ?? $value['node_type'] ?? '')));
            if ($type !== 'map' && $type !== 'project') {
                return null;
            }

            $rawId = $value['id'] ?? $value['target'] ?? $value['value'] ?? $value['uuid'] ?? null;
            $id = trim((string) ($rawId ?? ''));
            return $id !== '' ? $id : null;
        }

        if (!is_string($value)) {
            return null;
        }

        $raw = trim($value);
        if ($raw === '') {
            return null;
        }

        $colon = strpos($raw, ':');
        if ($colon !== false && $colon > 0) {
            $prefix = strtolower(trim(substr($raw, 0, $colon)));
            $remainder = trim(substr($raw, $colon + 1));
            if (($prefix === 'map' || $prefix === 'project') && $remainder !== '') {
                return $remainder;
            }
        }

        if (str_starts_with($raw, 'project-')) {
            return $raw;
        }

        return null;
    }

    private function resolveProjectForDmId(string $dmId): ?Project
    {
        $dmId = trim($dmId);
        if ($dmId === '') {
            return null;
        }

        $project = Project::where('dm_id', $dmId)->first();
        if ($project) {
            return $project;
        }

        if (preg_match('/^project-(\\d+)$/', $dmId, $m)) {
            return Project::find((int) $m[1]);
        }

        return null;
    }

    private function collectLinkedProjectDmIds(array $regions): array
    {
        $result = [];
        foreach ($regions as $region) {
            if (!is_array($region)) {
                continue;
            }
            $children = $region['children'] ?? null;
            if (!is_array($children)) {
                continue;
            }
            foreach ($children as $child) {
                $dmId = $this->parseChildProjectDmId($child);
                if ($dmId) {
                    $result[] = $dmId;
                }
            }
        }

        return array_values(array_unique($result));
    }

    private function mapProjectForViewer(Project $project, array $statuses, ?array $size = null): array
    {
        $imageUrl = $this->getImageUrl($project->image);
        $size = $size ?? ($this->tryGetImageSize($imageUrl) ?? ['width' => 1280, 'height' => 720]);

        $floors = $project->localities->map(function ($l) use ($statuses) {
            $statusId = $this->resolveStatusId($l, $statuses);
            $meta = is_array($l->metadata) ? $l->metadata : [];
            return [
                'id' => $l->dm_id ?: ('floor-' . $l->id),
                'name' => $l->name,
                'label' => $l->name,
                'designation' => $meta['designation'] ?? null,
                'prefix' => $meta['prefix'] ?? null,
                'suffix' => $meta['suffix'] ?? null,
                'url' => $meta['url'] ?? null,
                'detailUrl' => $meta['detailUrl'] ?? $meta['url'] ?? null,
                'type' => $l->type,
                'status' => $l->status,
                'statusId' => $statusId,
                'statusLabel' => $l->status_label,
                'statusColor' => $l->status_color,
                'area' => $l->area,
                'price' => $l->price,
                'rent' => $l->rent,
                'floor' => $l->floor,
                'meta' => $meta,
            ];
        })->values()->toArray();

        return [
            'id' => $project->dm_id ?: ('project-' . $project->id),
            'publicKey' => $project->map_key,
            'name' => $project->name,
            'image' => $imageUrl,
            'imageUrl' => $imageUrl,
            'renderer' => [
                'size' => $size,
            ],
            'palette' => [
                'statuses' => $statuses,
            ],
            'floors' => $floors,
            'regions' => is_array($project->regions) ? $project->regions : [],
            'frontend' => is_array($project->frontend) ? $project->frontend : null,
        ];
    }

    public function show(Request $request): JsonResponse
    {
        $key = trim((string) ($request->query('public_key') ?? $request->query('map_key') ?? $request->query('key') ?? ''));
        if ($key === '') {
            return response()->json(['message' => 'public_key is required'], 400);
        }

        $project = Project::with(['localities', 'parent'])->where('map_key', $key)->firstOrFail();

        $statuses = DmStatus::orderBy('sort_order')->get()->map(fn ($s) => [
            'id' => 'status-' . $s->id,
            'key' => mb_strtolower((string) $s->label),
            'name' => $s->name,
            'label' => $s->label,
            'color' => $s->color,
            'available' => (bool) $s->is_default,
        ])->toArray();

        $imageUrl = $this->getImageUrl($project->image);
        $size = $this->tryGetImageSize($imageUrl) ?? ['width' => 1280, 'height' => 720];
        $regions = is_array($project->regions) ? $project->regions : [];

        $viewerProject = $this->mapProjectForViewer($project, $statuses, $size);

        $linkedProjectIds = $this->collectLinkedProjectDmIds($regions);
        $linkedProjects = [];
        foreach ($linkedProjectIds as $linkedDmId) {
            $linked = $this->resolveProjectForDmId($linkedDmId);
            if (!$linked) {
                continue;
            }
            $linked->loadMissing('localities');
            $linkedProjects[] = $this->mapProjectForViewer($linked, $statuses);
        }

        // Collect hierarchical descendants based on parent_id (children, grandchildren, ...).
        $descendantIds = [];
        $frontier = [$project->id];
        while (!empty($frontier)) {
            $children = Project::query()
                ->whereIn('parent_id', $frontier)
                ->pluck('id')
                ->all();
            $children = array_values(array_diff($children, $descendantIds));
            if (empty($children)) {
                break;
            }
            $descendantIds = array_values(array_unique(array_merge($descendantIds, $children)));
            $frontier = $children;
        }

        $hierarchyProjects = [];
        if (!empty($descendantIds)) {
            $descendants = Project::with('localities')->whereIn('id', $descendantIds)->get();
            foreach ($descendants as $descendant) {
                $hierarchyProjects[] = $this->mapProjectForViewer($descendant, $statuses);
            }
        }

        // Ancestor chain for breadcrumbs / hierarchy helpers.
        $ancestors = [];
        $cursor = $project->parent;
        $guard = 0;
        while ($cursor && $guard < 25) {
            $ancestors[] = [
                'id' => $cursor->dm_id ?: ('project-' . $cursor->id),
                'publicKey' => $cursor->map_key,
                'name' => $cursor->name,
            ];
            $cursor = $cursor->parent;
            $guard += 1;
        }

        $accent = DmFrontendColor::getSelected()?->value ?? '#6366F1';

        return response()->json([
            'project' => $viewerProject,
            'ancestors' => $ancestors,
            'linkedProjects' => $linkedProjects,
            'hierarchyProjects' => $hierarchyProjects,
            'frontendAccentColor' => $accent,
        ]);
    }
}
