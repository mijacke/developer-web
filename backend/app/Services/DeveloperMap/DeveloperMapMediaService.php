<?php

namespace App\Services\DeveloperMap;

use App\Models\Project;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;
use InvalidArgumentException;

class DeveloperMapMediaService
{
    public function saveImageReference(string $entityId, string $attachmentId): void
    {
        if (str_starts_with($entityId, 'project-')) {
            $id = (int) str_replace('project-', '', $entityId);
            Project::where('id', $id)->update(['image' => $attachmentId]);
        }
    }

    public function uploadImage(UploadedFile $file): array
    {
        $maxSize = 10 * 1024 * 1024;
        if ($file->getSize() > $maxSize) {
            $sizeMB = round($file->getSize() / 1024 / 1024, 1);
            throw new InvalidArgumentException("Súbor je príliš veľký ({$sizeMB} MB). Maximálna veľkosť je 10 MB.");
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        $mimeType = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($mimeType, $allowedMimes, true) || !in_array($extension, $allowedExtensions, true)) {
            throw new InvalidArgumentException('Neplatný formát súboru. Povolené formáty: JPG, PNG, GIF, WebP.');
        }

        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
        $destination = public_path('uploads/maps');

        if (!is_dir($destination) && !mkdir($destination, 0755, true) && !is_dir($destination)) {
            throw new RuntimeException('Nepodarilo sa pripraviť priečinok pre upload.');
        }

        try {
            $file->move($destination, $filename);
        } catch (\Throwable $exception) {
            throw new RuntimeException('Nepodarilo sa uložiť súbor. Skúste to znova.', 0, $exception);
        }

        return [
            'url' => url('/uploads/maps/' . $filename),
            'filename' => $filename,
        ];
    }
}
