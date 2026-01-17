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
    /**
     * List all stored data for dm.js
     */
    public function list(): JsonResponse
    {
        return response()->json([
            'dm-projects' => Project::with('localities')->orderBy('sort_order')->get()->map(function ($p) {
                return [
                    'id' => 'project-' . $p->id,
                    'parentId' => $p->parent_id ? 'project-' . $p->parent_id : null,
                    'name' => $p->name,
                    'type' => $p->type,
                    'badge' => strtoupper(substr($p->name, 0, 2)),
                    'publicKey' => $p->map_key, // Frontend still expects publicKey prop
                    'image' => $p->image,
                    'imageUrl' => $p->image, // Frontend compatibility
                    'floors' => $p->localities->map(function ($l) {
                        return [
                            'id' => 'floor-' . $l->id,
                            'name' => $l->name,
                            'label' => $l->name,
                            'type' => $l->type,
                            'status' => $l->status,
                            'statusLabel' => $l->status,
                            'area' => $l->area,
                            'price' => $l->price,
                            'image' => $l->image,
                            'imageUrl' => $l->image, // Frontend compatibility
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
                // Update active state based on the value
                if (is_string($value)) {
                    DmFrontendColor::query()->update(['is_active' => false]);
                    // Try to find matching color and activate it, or just use it as custom
                    $color = DmFrontendColor::where('value', $value)->first();
                    if ($color) {
                        $color->update(['is_active' => true]);
                    } 
                    // Note: If no matching preset, we just rely on frontend sending the custom color value next time
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
        $incomingIds = [];
        
        // First pass: create/update all projects and collect ID mapping
        $idMapping = []; // Maps frontend temp IDs to database IDs
        
        foreach ($projects as $index => $projectData) {
            $rawId = $projectData['id'] ?? '';
            
            // Check if it's a new project (starts with 'new-')
            $isNew = str_starts_with($rawId, 'new-');
            
            // Extract numeric ID for existing projects (format: project-N)
            $projectId = str_replace(['new-project-', 'project-', 'new-'], '', $rawId);
            
            // Generate slug from project name for map_key
            $mapKey = $projectData['publicKey'] ?? $projectData['map_key'] ?? null;
            if (!$mapKey || str_starts_with($mapKey, 'pk_')) {
                // Generate a proper slug from the project name
                $mapKey = $this->slugify($projectData['name'] ?? 'mapa');
                // Ensure uniqueness
                $mapKey = $this->ensureUniqueMapKey($mapKey, $isNew ? null : (int)$projectId);
            }
            
            $data = [
                'name' => $projectData['name'] ?? 'Nový projekt',
                'type' => $projectData['type'] ?? null,
                'image' => $projectData['imageUrl'] ?? $projectData['image'] ?? null, // Map correctly to DB column 'image'
                'map_key' => $mapKey,
                'sort_order' => $index + 1,
            ];
            
            if (!$isNew && is_numeric($projectId)) {
                // Existing project - update
                $project = Project::find((int) $projectId);
                if ($project) {
                    $project->update($data);
                    $incomingIds[] = $project->id;
                    $idMapping[$rawId] = $project->id;
                }
            } else {
                // New project - create
                $newProject = Project::create($data);
                $incomingIds[] = $newProject->id;
                $idMapping[$rawId] = $newProject->id;
            }
        }
        
        // Second pass: update parent_id relationships
        foreach ($projects as $projectData) {
            $projectId = str_replace(['new-project-', 'project-', 'new-'], '', $projectData['id'] ?? '');
            // Also handle new-project IDs in second pass correctly
            $rawId = $projectData['id'] ?? '';
            
            $parentValue = $projectData['parentId'] ?? $projectData['parent_id'] ?? null;
            
            // Determine the database ID of this project
            $dbId = null;
            if (isset($idMapping[$rawId])) {
                $dbId = $idMapping[$rawId];
            } elseif (is_numeric($projectId)) {
                $dbId = (int) $projectId;
            }
            
            if (!$dbId) {
                continue;
            }
            
            // Resolve parent_id
            $parentId = null;
            if ($parentValue !== null && $parentValue !== '' && $parentValue !== 'none') {
                $parentIdRaw = str_replace(['new-project-', 'project-', 'new-'], '', (string) $parentValue);
                
                // Check if we have a mapping for this parent ID (it might be a new project too)
                if (isset($idMapping[$parentValue])) {
                    $parentId = $idMapping[$parentValue];
                } elseif (is_numeric($parentIdRaw)) {
                    $parentId = (int) $parentIdRaw;
                }
            }
            
            // Update parent_id
            Project::where('id', $dbId)->update(['parent_id' => $parentId]);
        }

        // Delete projects that are not in the incoming list
        if (!empty($incomingIds)) {
            Project::whereNotIn('id', $incomingIds)->delete();
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
        // Reset all to inactive
        DmFrontendColor::query()->update(['is_active' => false]);

        // Try to find exact match in preset colors
        $match = DmFrontendColor::where('value', $colorValue)
            ->where('name', '!=', 'Vlastná')
            ->first();
        
        if ($match) {
            // Found matching preset color
            $match->update(['is_active' => true]);
        } else {
            // Custom color - update "Vlastná" with the new value and set as active
            DmFrontendColor::where('name', 'Vlastná')->update([
                'value' => $colorValue,
                'is_active' => true
            ]);
        }
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
