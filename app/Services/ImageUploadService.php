<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class ImageUploadService
{
    /**
     * Process and store an uploaded image into storage/uploads/{folder} without storage:link.
     * Automatically converts and compresses images to WebP if GD is supported,
     * or falls back to moving the original file.
     */
    public function upload(
        UploadedFile $file,
        string $folder,
        string $prefix = 'img_',
        int $maxWidth = 1200,
        int $quality = 80
    ): string {
        $dir = storage_path('uploads/'.trim($folder, '/\\'));

        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true, true);
        }

        $imageInfo = @getimagesize($file->getPathname());
        $mime = $imageInfo ? ($imageInfo['mime'] ?? '') : '';

        $source = match ($mime) {
            'image/jpeg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($file->getPathname()) : null,
            'image/png' => function_exists('imagecreatefrompng') ? @imagecreatefrompng($file->getPathname()) : null,
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($file->getPathname()) : null,
            default => null,
        };

        if (! $source || ! function_exists('imagewebp')) {
            $extension = $file->getClientOriginalExtension() ?: 'jpg';
            $imageName = $prefix.time().'_'.uniqid().'.'.strtolower($extension);
            $file->move($dir, $imageName);

            return $imageName;
        }

        [$origWidth, $origHeight] = $imageInfo;

        if ($origWidth <= $maxWidth) {
            $newWidth = $origWidth;
            $newHeight = $origHeight;
        } else {
            $ratio = $maxWidth / $origWidth;
            $newWidth = $maxWidth;
            $newHeight = (int) round($origHeight * $ratio);
        }

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        $imageName = $prefix.time().'_'.uniqid().'.webp';
        $destPath = $dir.DIRECTORY_SEPARATOR.$imageName;

        imagewebp($resized, $destPath, $quality);

        imagedestroy($source);
        imagedestroy($resized);

        return $imageName;
    }

    /**
     * Delete an uploaded image file from storage/uploads/{folder}.
     */
    public function delete(?string $filename, string $folder): bool
    {
        if ($filename === null || trim($filename) === '') {
            return false;
        }

        $baseName = basename($filename);
        $path = storage_path('uploads/'.trim($folder, '/\\').DIRECTORY_SEPARATOR.$baseName);

        if (File::exists($path)) {
            return File::delete($path);
        }

        // Check relative path fallback
        $fallback = storage_path('uploads/'.ltrim($filename, '/\\'));
        if (File::exists($fallback)) {
            return File::delete($fallback);
        }

        return false;
    }

    /**
     * Get the public URL for an uploaded file without requiring storage:link.
     */
    public function url(?string $filename, string $folder, ?string $default = null): ?string
    {
        if ($filename === null || trim($filename) === '') {
            return $default;
        }

        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
            return $filename;
        }

        $baseName = basename($filename);

        return url('uploads/'.trim($folder, '/').'/'.$baseName);
    }

    /**
     * Get the absolute filesystem path for an uploaded image.
     */
    public function path(?string $filename, string $folder): ?string
    {
        if ($filename === null || trim($filename) === '') {
            return null;
        }

        $baseName = basename($filename);

        return storage_path('uploads/'.trim($folder, '/\\').DIRECTORY_SEPARATOR.$baseName);
    }
}
