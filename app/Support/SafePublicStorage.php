<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

trait SafePublicStorage
{
    protected function storeUploadedGalleryImages(Request $request, string $field, string $directory): array
    {
        $paths = [];
        try {
            foreach ($request->file($field, []) as $file) {
                $paths[] = OptimizedImage::store($file, $directory);
            }
        } catch (\Throwable $exception) {
            foreach ($paths as $path) {
                $this->deletePublicUpload($path, $directory.'/');
            }
            throw $exception;
        }
        return $paths;
    }

    protected function syncGalleryImages(array $currentImages, array $imagesToRemove, array $newImages, string $allowedPrefix, int $limit): array
    {
        $imagesToRemove = array_values(array_intersect($imagesToRemove, $currentImages));
        $keptImages = array_values(array_diff($currentImages, $imagesToRemove));
        $limitedImages = array_slice(array_merge($keptImages, $newImages), 0, $limit);
        foreach (array_diff($newImages, $limitedImages) as $unusedImage) {
            $this->deletePublicUpload($unusedImage, $allowedPrefix);
        }
        return $limitedImages;
    }

    protected function deletePublicUpload(?string $path, array|string $allowedPrefixes): void
    {
        if (! $path || Str::contains($path, ['..', '\\'])) {
            return;
        }

        $allowedPrefixes = (array) $allowedPrefixes;

        if (! Str::startsWith($path, $allowedPrefixes)) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
