<?php

namespace App\Services;

use App\Models\Inspection;
use Illuminate\Http\UploadedFile;

class PhotoStorage
{
    public function __construct(
        protected ImageUploadService $imageUploadService
    ) {}

    /**
     * Store validated inspection photos into storage/uploads/inspections and attach them to
     * the inspection record. Files are compressed and converted to WebP.
     *
     * @param  array<int, mixed>  $files
     */
    public function store(Inspection $inspection, array $files): void
    {
        foreach (array_filter($files) as $file) {
            if (! $file instanceof UploadedFile || ! $file->isValid()) {
                continue;
            }

            $filename = $this->imageUploadService->upload($file, 'inspections', 'insp_');

            $inspection->photos()->create([
                'path' => $filename,
            ]);
        }
    }
}
