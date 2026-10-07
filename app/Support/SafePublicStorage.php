<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait SafePublicStorage
{
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
