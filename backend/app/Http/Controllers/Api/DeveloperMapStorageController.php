<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DmMapColor;
use App\Models\DmFrontendColor;
use App\Models\DmFont;
use App\Models\DmStatus;
use App\Models\DmType;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            'dm-projects' => Project::with('localities')->get()->map(function ($p) {
                return [
                    'id' => 'project-' . $p->id,
                    'name' => $p->name,
                    'type' => $p->type,
                    'badge' => strtoupper(substr($p->name, 0, 2)),
                    'publicKey' => $p->public_key,
                    'image' => $p->image_url,
                    'imageUrl' => $p->image_url,
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
                            'image' => $l->image_url,
                            'imageUrl' => $l->image_url,
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
            'dm-fonts' => DmFont::getAll(),
            'dm-selected-font' => ['id' => DmFont::getSelected()],
            'dm-frontend-colors' => DmFrontendColor::orderBy('sort_order')->get()->map(fn($c) => [
                'id' => 'frontend-color-' . $c->id,
                'name' => $c->name,
                'label' => $c->label,
                'value' => $c->value,
                'isDefault' => $c->is_default,
                'isCustom' => $c->is_custom,
            ])->toArray(),
            'dm-frontend-accent-color' => DmFrontendColor::getSelected()?->value ?? '#6366F1',
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

        $data = $this->list()->getData(true);
        $value = $data[$key] ?? null;

        return response()->json([
            'key' => $key,
            'value' => $value,
        ]);
    }

    /**
     * Set a value - routes to appropriate model
     */
    public function set(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'value' => 'nullable',
        ]);

        $key = $validated['key'];
        $value = $validated['value'];

        // Route to appropriate handler based on key
        if ($key === 'dm-frontend-accent-color') {
            // Frontend sends specific color value or ID update request
            // For now assume we just need to read it back, but if we need to set specific color
            // Logic: reset all defaults, set specific one as default
            // If it's a value (hex), update "Vlastná" or find matching
             if (is_string($value)) {
                $this->updateFrontendAccentColor($value);
             }
        } elseif ($key === 'dm-selected-font') {
            $fontId = is_array($value) ? ($value['id'] ?? 'Inter') : $value;
            DmFont::setSelected($fontId);
        } elseif ($key === 'dm-projects' && is_array($value)) {
            $this->syncProjects($value);
        } elseif ($key === 'dm-statuses' && is_array($value)) {
            $this->syncStatuses($value);
        } elseif ($key === 'dm-types' && is_array($value)) {
            $this->syncTypes($value);
        } elseif ($key === 'dm-colors' && is_array($value)) {
            $this->syncMapColors($value);
        }

        return response()->json([
            'success' => true,
            'key' => $key,
        ]);
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
            Project::where('id', $id)->update(['image_url' => $imageUrl]);
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
     */
    private function syncProjects(array $projects): void
    {
        foreach ($projects as $projectData) {
            $projectId = str_replace('project-', '', $projectData['id'] ?? '');
            
            if (is_numeric($projectId)) {
                Project::where('id', $projectId)->update([
                    'name' => $projectData['name'] ?? '',
                    'type' => $projectData['type'] ?? 'residential',
                    'image_url' => $projectData['imageUrl'] ?? $projectData['image'] ?? null,
                ]);
            } else {
                Project::create([
                    'name' => $projectData['name'] ?? 'Nový projekt',
                    'type' => $projectData['type'] ?? 'residential',
                    'public_key' => $projectData['publicKey'] ?? uniqid('pk_'),
                    'image_url' => $projectData['imageUrl'] ?? $projectData['image'] ?? null,
                ]);
            }
        }
    }

    /**
     * Sync statuses from dm.js data
     */
    private function syncStatuses(array $statuses): void
    {
        foreach ($statuses as $index => $statusData) {
            $statusId = str_replace('status-', '', $statusData['id'] ?? '');
            
            $data = [
                'name' => $statusData['name'] ?? '',
                'label' => $statusData['label'] ?? '',
                'color' => $statusData['color'] ?? '#6B7280',
                'is_default' => $statusData['isDefault'] ?? false,
                'sort_order' => $index + 1,
            ];

            if (is_numeric($statusId)) {
                DmStatus::where('id', $statusId)->update($data);
            } else {
                DmStatus::create($data);
            }
        }
    }

    /**
     * Sync types from dm.js data
     */
    private function syncTypes(array $types): void
    {
        foreach ($types as $index => $typeData) {
            $typeId = str_replace('type-', '', $typeData['id'] ?? '');
            
            $data = [
                'name' => $typeData['name'] ?? '',
                'label' => $typeData['label'] ?? '',
                'color' => $typeData['color'] ?? '#405ECD',
                'sort_order' => $index + 1,
            ];

            if (is_numeric($typeId)) {
                DmType::where('id', $typeId)->update($data);
            } else {
                DmType::create($data);
            }
        }
    }

    /**
     * Sync map colors from dm.js data
     */
    private function syncMapColors(array $colors): void
    {
        foreach ($colors as $index => $colorData) {
            $colorId = str_replace('color-', '', $colorData['id'] ?? '');
            
            $data = [
                'name' => $colorData['name'] ?? '',
                'label' => $colorData['label'] ?? '',
                'value' => $colorData['value'] ?? '#FFFFFF',
                'sort_order' => $index + 1,
            ];

            if (is_numeric($colorId)) {
                DmMapColor::where('id', $colorId)->update($data);
            } else {
                DmMapColor::create($data);
            }
        }
    }

    /**
     * Update frontend accent color selection
     */
    private function updateFrontendAccentColor(string $colorValue): void
    {
        // First reset all defaults
        DmFrontendColor::query()->update(['is_default' => false]);

        // Try to find exact match
        $match = DmFrontendColor::where('value', $colorValue)->where('is_custom', false)->first();
        
        if ($match) {
            $match->update(['is_default' => true]);
        } else {
            // Must be custom
            DmFrontendColor::where('is_custom', true)->update([
                'value' => $colorValue,
                'is_default' => true
            ]);
        }
    }
}
