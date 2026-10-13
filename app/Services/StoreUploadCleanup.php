<?php

namespace App\Services;

use App\Models\Store;
use App\Support\SafePublicStorage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreUploadCleanup
{
    use SafePublicStorage;

    public function collect(Store $store): array
    {
        $store->loadMissing(['products', 'paymentProofs']);
        $public = array_merge([$store->logo], $store->gallery_images ?? []);
        foreach ($store->products as $product) {
            $public = array_merge($public, [$product->image], $product->gallery_images ?? []);
        }
        $private = $store->paymentProofs->pluck('proof_path')->all();
        return ['public' => array_filter(array_merge($public, $private)), 'private' => $private];
    }

    public function remove(array $uploads): void
    {
        foreach ($uploads['public'] as $path) {
            try {
                $this->deletePublicUpload($path, ['products/', 'stores/', 'payment-proofs/']);
            } catch (\Throwable $exception) {
                report($exception);
            }
        }
        foreach ($uploads['private'] as $path) {
            if (! is_string($path) || ! Str::startsWith($path, 'payment-proofs/') || Str::contains($path, ['..', '\\'])) {
                continue;
            }
            try {
                Storage::disk('local')->delete($path);
            } catch (\Throwable $exception) {
                report($exception);
            }
        }
    }
}
