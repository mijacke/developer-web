<?php

namespace App\Repositories;

use App\Models\DmMapColor;
use App\Models\DmStatus;
use App\Models\DmType;
use Illuminate\Support\Collection;

class MapPaletteRepository
{
    public function getStatuses(): Collection
    {
        return DmStatus::orderBy('sort_order')->get();
    }

    public function getTypes(): Collection
    {
        return DmType::orderBy('sort_order')->get();
    }

    public function getColors(): Collection
    {
        return DmMapColor::orderBy('sort_order')->get();
    }

    public function syncStatuses(array $statuses): void
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

        if (!empty($incomingIds)) {
            DmStatus::whereNotIn('id', $incomingIds)->delete();
        }
    }

    public function syncTypes(array $types): void
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

        if (!empty($incomingIds)) {
            DmType::whereNotIn('id', $incomingIds)->delete();
        }
    }

    public function syncColors(array $colors): void
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

        if (!empty($incomingIds)) {
            DmMapColor::whereNotIn('id', $incomingIds)->delete();
        }
    }

    public function deleteTypeById(int $typeId): int
    {
        return DmType::where('id', $typeId)->delete();
    }

    public function deleteStatusById(int $statusId): int
    {
        return DmStatus::where('id', $statusId)->delete();
    }
}
