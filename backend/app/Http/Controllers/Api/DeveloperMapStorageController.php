<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DmMapColor;
use App\Models\DmFrontendColor;
// use App\Models\DmFont;
use App\Models\DmStatus;
use App\Models\DmType;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Storage controller for Developer Map dashboard.
 * Uses structured database tables.
 */
class DeveloperMapStorageController extends Controller
{
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

    /**
     * Normalise a potentially-localised numeric input (e.g. "1 234,50") into a DB-safe decimal string.
     */
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

        // Keep only digits and common separators/signs; strip currency/unit symbols.
        $clean = preg_replace('/[^0-9,\\.\\-]/', '', $raw) ?? '';
        $clean = trim($clean);
        if ($clean === '' || $clean === '-' || $clean === '.' || $clean === ',') {
            return null;
        }

        $lastComma = strrpos($clean, ',');
        $lastDot = strrpos($clean, '.');

        // If both separators exist, treat the last one as decimal separator and drop the other as thousands separator.
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

        // Remove any remaining thousands separators (e.g. "1.234.567").
        if (substr_count($clean, '.') > 1) {
            $parts = explode('.', $clean);
            $last = array_pop($parts);
            $clean = implode('', $parts) . '.' . $last;
        }

        $clean = trim($clean);
        if (!preg_match('/^-?\\d+(?:\\.\\d+)?$/', $clean)) {
            return null;
        }

        return $clean;
    }

    /**
     * Convert a relative image path to a full URL
     */
    private function getImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        // If already an absolute URL, return as-is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        // Return full URL using app URL
        return url($path);
    }

    /**
     * List all stored data for dm.js
     */
    public function list(): JsonResponse
    {
        return response()->json([
            'dm-projects' => Project::with('localities')->orderBy('sort_order')->get()->map(function ($p) {
                return [
                    'id' => $p->dm_id ?: ('project-' . $p->id),
                    'parentId' => $p->parent?->dm_id ?: ($p->parent_id ? 'project-' . $p->parent_id : null),
                    'name' => $p->name,
                    'type' => $p->type,
                    'badge' => strtoupper(substr($p->name, 0, 2)),
                    'publicKey' => $p->map_key, // Frontend still expects publicKey prop
                    'image' => $this->getImageUrl($p->image),
                    'imageUrl' => $this->getImageUrl($p->image), // Frontend compatibility
                    'regions' => is_array($p->regions) ? $p->regions : [],
                    'frontend' => is_array($p->frontend) ? $p->frontend : null,
                    'floors' => $p->localities->map(function ($l) {
                        $meta = is_array($l->metadata) ? $l->metadata : [];
                        return [
                            'id' => $l->dm_id ?: ('floor-' . $l->id),
                            'name' => $l->name,
                            'label' => $l->name,
                            'designation' => $meta['designation'] ?? null,
                            'prefix' => $meta['prefix'] ?? null,
                            'suffix' => $meta['suffix'] ?? null,
                            'url' => $meta['url'] ?? null,
                            'detailUrl' => $meta['detailUrl'] ?? null,
                            'statusId' => $meta['statusId'] ?? null,
                            'type' => $l->type,
                            'status' => $l->status,
                            'statusLabel' => $l->status,
                            'area' => $l->area,
                            'price' => $l->price,
                            'rent' => $l->rent,
                            'floor' => $l->floor,
                            'image' => $this->getImageUrl($l->image),
                            'imageUrl' => $this->getImageUrl($l->image), // Frontend compatibility
                            'svgPath' => $l->svg_path,
                            'regions' => is_array($l->regions) ? $l->regions : [],
                        ];
                    })->toArray(),
                ];
            })->toArray(),
            'dm-statuses' => DmStatus::orderBy('sort_order')->get()->map(fn($s) => [
                'id' => 'status-' . $s->id,
                'name' => $s->name,
                'label' => $s->label,
                'color' => $s->color,
                'isDefault' => $s->is_default,
            ])->toArray(),
            'dm-types' => DmType::orderBy('sort_order')->get()->map(fn($t) => [
                'id' => 'type-' . $t->id,
                'name' => $t->name,
                'label' => $t->label,
                'color' => $t->color,
            ])->toArray(),
            'dm-colors' => DmMapColor::orderBy('sort_order')->get()->map(fn($c) => [
                'id' => 'color-' . $c->id,
                'name' => $c->name,
                'label' => $c->label,
                'value' => $c->value,
            ])->toArray(),
            // Fonts removed from DB as per user request
            'dm-frontend-colors' => DmFrontendColor::get()->map(fn($c) => [
                'id' => 'frontend-color-' . $c->id,
                'name' => $c->name,
                'value' => $c->value,
                'isActive' => (bool) $c->is_active,
            ])->toArray(),
            'dm-frontend-accent-color' => DmFrontendColor::where('is_active', true)->first()?->value ?? '#6366F1',
        ]);
    }

    /**
     * Get a value by key
     */
    public function get(Request $request): JsonResponse
    {
        $key = $request->query('key');
        
        if (!$key) {
            return response()->json(['message' => 'Key is required'], 400);
        }

        // Return empty structure for projects if empty to prevent errors
        if ($key === 'dm-projects' && Project::count() === 0) {
            return response()->json(['value' => []]);
        }

        return $this->list();
    }

    /**
     * Set a value by key
     */
    public function set(Request $request): JsonResponse
    {
        $key = $request->input('key');
        $value = $request->input('value');

        if (!$key) {
            return response()->json(['message' => 'Key is required'], 400);
        }

        switch ($key) {
            case 'dm-projects':
                $this->syncProjects($value ?? []);
                break;
            case 'dm-statuses':
                $this->syncStatuses($value ?? []);
                break;
            case 'dm-types':
                $this->syncTypes($value ?? []);
                break;
            case 'dm-colors':
                $this->syncMapColors($value ?? []);
                break;
            case 'dm-frontend-colors':
                $this->syncFrontendColors($value ?? []);
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
        }

        return response()->json(['success' => true, 'key' => $key]);
    }

    /**
     * Remove a value
     */
    public function remove(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Use specific delete endpoints instead',
        ]);
    }

    /**
     * Migrate data (not needed with structured tables)
     */
    public function migrate(Request $request): JsonResponse
    {
        return response()->json([
            'migrated' => [],
            'skipped' => [],
            'message' => 'Migration not needed - using structured tables',
        ]);
    }

    /**
     * Save image reference
     */
    public function saveImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'entity_id' => 'required|string',
            'attachment_id' => 'required',
        ]);

        // Parse entity ID and update appropriate model
        $entityId = $validated['entity_id'];
        $imageUrl = $validated['attachment_id'];

        if (str_starts_with($entityId, 'project-')) {
            $id = (int) str_replace('project-', '', $entityId);
            Project::where('id', $id)->update(['image' => $imageUrl]);
        }

        return response()->json([
            'success' => true,
            'entity_id' => $entityId,
        ]);
    }

    /**
     * Upload an image file from the frontend
     */
    public function uploadImage(Request $request): JsonResponse
    {
        // Check if file was uploaded
        if (!$request->hasFile('image')) {
            return response()->json([
                'success' => false,
                'error' => 'no_file',
                'message' => 'Nebol nahratý žiadny súbor.',
            ], 400);
        }

        $file = $request->file('image');

        // Check file size (10MB max = 10 * 1024 * 1024 = 10485760 bytes)
        $maxSize = 10 * 1024 * 1024; // 10MB
        if ($file->getSize() > $maxSize) {
            $sizeMB = round($file->getSize() / 1024 / 1024, 1);
            return response()->json([
                'success' => false,
                'error' => 'file_too_large',
                'message' => "Súbor je príliš veľký ({$sizeMB} MB). Maximálna veľkosť je 10 MB.",
            ], 400);
        }

        // Check file type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        $mimeType = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($mimeType, $allowedMimes) || !in_array($extension, $allowedExtensions)) {
            return response()->json([
                'success' => false,
                'error' => 'invalid_type',
                'message' => 'Neplatný formát súboru. Povolené formáty: JPG, PNG, GIF, WebP.',
            ], 400);
        }

        // Generate unique filename
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
        
        try {
            // Store in public/uploads/maps directory
            $file->move(public_path('uploads/maps'), $filename);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'upload_failed',
                'message' => 'Nepodarilo sa uložiť súbor. Skúste to znova.',
            ], 500);
        }
        
        // Return the full public URL
        $url = url('/uploads/maps/' . $filename);

        return response()->json([
            'success' => true,
            'url' => $url,
            'filename' => $filename,
        ]);
    }

    /**
     * Get bootstrap data for the app
     */
    public function bootstrap(): JsonResponse
    {
        return $this->list();
    }

    /**
     * Sync projects from dm.js data
     * Supports hierarchical structure with parent_id
     */
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

            if (preg_match('/^project-(\\d+)$/', $dmId, $m)) {
                $byId = Project::find((int) $m[1]);
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
            $label = preg_replace('/\\s+/', ' ', $label) ?? $label;
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

        // 1) Create/update projects
        foreach ($projects as $index => $projectData) {
            $rawId = $projectData['id'] ?? null;
            $dmId = $normaliseId(is_string($rawId) ? $rawId : (string) $rawId, 'project');
            if (!$dmId) {
                continue;
            }

            // Generate slug from project name for map_key
            $mapKey = $projectData['publicKey'] ?? $projectData['map_key'] ?? null;
            $existing = $findProjectForDmId($dmId, is_string($mapKey) ? $mapKey : null);
            $project = $existing;
            if (!$mapKey || (is_string($mapKey) && str_starts_with($mapKey, 'pk_'))) {
                // Generate a proper slug from the project name
                $mapKey = $this->slugify($projectData['name'] ?? 'mapa');
                // Ensure uniqueness
                $mapKey = $this->ensureUniqueMapKey($mapKey, $existing?->id);
            }
            
            $data = [
                'dm_id' => $dmId,
                'name' => $projectData['name'] ?? 'Nový projekt',
                'type' => $projectData['type'] ?? null,
                'image' => $projectData['imageUrl'] ?? $projectData['image'] ?? null, // Map correctly to DB column 'image'
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

            // 2) Sync floors (localities) for this project
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
                    'metadata' => array_filter($metadata, fn ($v) => $v !== null && $v !== ''),
                    'sort_order' => $floorIndex + 1,
                ];

                $locality = \App\Models\Locality::where('dm_id', $floorDmId)->first();
                if (!$locality && preg_match('/^floor-(\\d+)$/', $floorDmId, $m)) {
                    $locality = \App\Models\Locality::find((int) $m[1]);
                }
                if ($locality) {
                    $locality->update($localityData);
                    if (!$locality->dm_id) {
                        $locality->update(['dm_id' => $floorDmId]);
                    }
                } else {
                    \App\Models\Locality::create($localityData);
                }
            }

            if (!empty($incomingFloorDmIds)) {
                \App\Models\Locality::where('project_id', $project->id)
                    ->whereNotIn('dm_id', $incomingFloorDmIds)
                    ->delete();
            } else {
                \App\Models\Locality::where('project_id', $project->id)->delete();
            }
        }

        // 3) Update parent_id relationships
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

        // 3b) Recalculate project sort_order (alphabetical, respecting hierarchy)
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

            // Add any leftover projects (cycle/orphan safety) in stable alphabetical order.
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

        // 4) Delete projects not in incoming list
        if (!empty($incomingProjectIds)) {
            Project::whereNotIn('id', $incomingProjectIds)->delete();
        }
    }


    /**
     * Sync statuses from dm.js data
     */
    private function syncStatuses(array $statuses): void
    {
        $incomingIds = [];

        foreach ($statuses as $index => $statusData) {
            $statusId = str_replace('status-', '', $statusData['id'] ?? '');
            
            $data = [
                'name' => $statusData['name'] ?? $statusData['label'] ?? '',
                'label' => $statusData['label'] ?? $statusData['name'] ?? '',
                'color' => $statusData['color'] ?? '#6B7280',
                'is_default' => $statusData['isDefault'] ?? false,
                'sort_order' => $index + 1,
            ];

            if (is_numeric($statusId)) {
                // Use updateOrCreate to handle both existing and new records
                $status = DmStatus::updateOrCreate(
                    ['id' => (int) $statusId],
                    $data
                );
                $incomingIds[] = $status->id;
            } else {
                $newStatus = DmStatus::create($data);
                $incomingIds[] = $newStatus->id;
            }
        }

        // Delete statuses that are not in the incoming list
        if (!empty($incomingIds)) {
            DmStatus::whereNotIn('id', $incomingIds)->delete();
        }
    }

    /**
     * Sync types from dm.js data
     */
    private function syncTypes(array $types): void
    {
        $incomingIds = [];

        foreach ($types as $index => $typeData) {
            $typeId = str_replace('type-', '', $typeData['id'] ?? '');
            
            $data = [
                'name' => $typeData['name'] ?? $typeData['label'] ?? '',
                'label' => $typeData['label'] ?? $typeData['name'] ?? '',
                'color' => $typeData['color'] ?? '#405ECD',
                'sort_order' => $index + 1,
            ];

            if (is_numeric($typeId)) {
                // Use updateOrCreate to handle both existing and new records
                $type = DmType::updateOrCreate(
                    ['id' => (int) $typeId],
                    $data
                );
                $incomingIds[] = $type->id;
            } else {
                $newType = DmType::create($data);
                $incomingIds[] = $newType->id;
            }
        }

        // Delete types that are not in the incoming list
        if (!empty($incomingIds)) {
            DmType::whereNotIn('id', $incomingIds)->delete();
        }
    }

    /**
     * Sync map colors from dm.js data
     */
    private function syncMapColors(array $colors): void
    {
        $incomingIds = [];

        foreach ($colors as $index => $colorData) {
            $colorId = str_replace('color-', '', $colorData['id'] ?? '');
            
            $data = [
                'name' => $colorData['name'] ?? '',
                'label' => $colorData['label'] ?? '',
                'value' => $colorData['value'] ?? '#FFFFFF',
                'sort_order' => $index + 1,
            ];

            if (is_numeric($colorId)) {
                $color = DmMapColor::updateOrCreate(
                    ['id' => (int) $colorId],
                    $data
                );
                $incomingIds[] = $color->id;
            } else {
                $newColor = DmMapColor::create($data);
                $incomingIds[] = $newColor->id;
            }
        }

        // Delete colors that are not in the incoming list
        if (!empty($incomingIds)) {
            DmMapColor::whereNotIn('id', $incomingIds)->delete();
        }
    }

    /**
     * Sync frontend colors from dm.js data
     */
    private function syncFrontendColors(array $colors): void
    {
        $incomingIds = [];

        foreach ($colors as $index => $colorData) {
            $colorId = str_replace('frontend-color-', '', $colorData['id'] ?? '');
            
            $data = [
                'name' => $colorData['name'] ?? '',
                'value' => $colorData['value'] ?? '#FFFFFF',
                'is_active' => $colorData['isActive'] ?? false,
                'sort_order' => $index + 1, // Assuming sort_order might be needed here too
            ];

            if (is_numeric($colorId)) {
                $color = DmFrontendColor::updateOrCreate(
                    ['id' => (int) $colorId],
                    $data
                );
                $incomingIds[] = $color->id;
            } else {
                $newColor = DmFrontendColor::create($data);
                $incomingIds[] = $newColor->id;
            }
        }

        // Delete colors that are not in the incoming list
        if (!empty($incomingIds)) {
            DmFrontendColor::whereNotIn('id', $incomingIds)->delete();
        }
    }

    /**
     * Update frontend accent color selection
     */
    private function updateFrontendAccentColor(string $colorValue): void
    {
        $colorValue = strtoupper(trim($colorValue));
        if (!preg_match('/^#[0-9A-F]{6}$/', $colorValue)) {
            return;
        }

        // Reset all to inactive
        DmFrontendColor::query()->update(['is_active' => false]);

        // Try to find exact match in preset colors
        $match = DmFrontendColor::where('value', $colorValue)
            ->where('name', '!=', 'Vlastná')
            ->first();
        
        if ($match) {
            // Found matching preset color
            $match->update(['is_active' => true]);
            return;
        }

        // Custom color - update (or create) "Vlastná" with the new value and set as active
        $custom = DmFrontendColor::where('name', 'Vlastná')->first();
        if (!$custom) {
            $custom = DmFrontendColor::create([
                'name' => 'Vlastná',
                'value' => $colorValue,
                'is_active' => true,
            ]);
            return;
        }

        $custom->update([
            'value' => $colorValue,
            'is_active' => true,
        ]);
    }

    /**
     * Delete a type by ID
     */
    public function deleteType(string $id): JsonResponse
    {
        // Handle both "type-5" format and plain "5"
        $typeId = str_replace('type-', '', $id);
        
        if (!is_numeric($typeId)) {
            return response()->json(['success' => false, 'message' => 'Invalid type ID'], 400);
        }

        $deleted = DmType::where('id', $typeId)->delete();

        return response()->json([
            'success' => $deleted > 0,
            'deleted_id' => $typeId,
        ]);
    }

    /**
     * Delete a status by ID
     */
    public function deleteStatus(string $id): JsonResponse
    {
        // Handle both "status-5" format and plain "5"
        $statusId = str_replace('status-', '', $id);
        
        if (!is_numeric($statusId)) {
            return response()->json(['success' => false, 'message' => 'Invalid status ID'], 400);
        }

        $deleted = DmStatus::where('id', $statusId)->delete();

        return response()->json([
            'success' => $deleted > 0,
            'deleted_id' => $statusId,
        ]);
    }

    /**
     * Generate a URL-friendly slug from a string
     */
    private function slugify(string $input): string
    {
        // Transliterate Slovak/Czech characters
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

    /**
     * Ensure map_key is unique in the database
     */
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
