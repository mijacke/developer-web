<?php

namespace App\Services;

use App\Models\DmFrontendColor;
use Illuminate\Support\Collection;

class FrontendColorService
{
    public function all(): Collection
    {
        return DmFrontendColor::query()->get();
    }

    public function getAccentColor(string $fallback = '#6366F1'): string
    {
        return DmFrontendColor::where('is_active', true)->first()?->value ?? $fallback;
    }

    public function sync(array $colors): void
    {
        $incomingIds = [];

        foreach ($colors as $index => $colorData) {
            $colorId = str_replace('frontend-color-', '', $colorData['id'] ?? '');

            $data = [
                'name' => $colorData['name'] ?? '',
                'value' => $colorData['value'] ?? '#FFFFFF',
                'is_active' => $colorData['isActive'] ?? false,
                'sort_order' => $index + 1,
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

        if (!empty($incomingIds)) {
            DmFrontendColor::whereNotIn('id', $incomingIds)->delete();
        }
    }

    public function updateAccentColor(string $colorValue): void
    {
        $colorValue = strtoupper(trim($colorValue));
        if (!preg_match('/^#[0-9A-F]{6}$/', $colorValue)) {
            return;
        }

        DmFrontendColor::query()->update(['is_active' => false]);

        $match = DmFrontendColor::where('value', $colorValue)
            ->where('name', '!=', 'Vlastná')
            ->first();

        if ($match) {
            $match->update(['is_active' => true]);
            return;
        }

        $custom = DmFrontendColor::where('name', 'Vlastná')->first();
        if (!$custom) {
            DmFrontendColor::create([
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
}
