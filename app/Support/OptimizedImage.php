<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OptimizedImage
{
    public static function store(UploadedFile $file, string $directory): string
    {
        if (! function_exists('imagewebp')) {
            $path = $file->store($directory, 'public');
            if (! $path) { throw new \RuntimeException('No se pudo guardar la imagen.'); }
            return $path;
        }
        $image = @imagecreatefromstring(file_get_contents($file->getRealPath()));
        if (! $image) { throw new \RuntimeException('La imagen no se pudo procesar.'); }
        $resized = null;
        try {
            if ($file->getMimeType() === 'image/jpeg' && function_exists('exif_read_data')) {
                $exif = @exif_read_data($file->getRealPath());
                $orientation = $exif['Orientation'] ?? 1;
                if (in_array($orientation, [2, 4, 5, 7], true)) { imageflip($image, IMG_FLIP_HORIZONTAL); }
                $angle = match ($orientation) { 3, 4 => 180, 5, 6 => -90, 7, 8 => 90, default => 0 };
                if ($angle) {
                    $rotated = imagerotate($image, $angle, 0);
                    imagedestroy($image);
                    $image = $rotated;
                }
            }
            $scale = min(1, 1600 / max(imagesx($image), imagesy($image)));
            $resized = imagecreatetruecolor(max(1, (int) round(imagesx($image) * $scale)), max(1, (int) round(imagesy($image) * $scale)));
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, imagesx($resized), imagesy($resized), imagesx($image), imagesy($image));
            ob_start();
            try {
                if (! imagewebp($resized, null, 82)) { throw new \RuntimeException('No se pudo optimizar la imagen.'); }
                $contents = ob_get_contents();
            } finally { ob_end_clean(); }
            $path = trim($directory, '/').'/'.Str::uuid().'.webp';
            if (! Storage::disk('public')->put($path, $contents)) { throw new \RuntimeException('No se pudo guardar la imagen.'); }
            return $path;
        } finally {
            imagedestroy($image);
            if ($resized) { imagedestroy($resized); }
        }
    }
}
