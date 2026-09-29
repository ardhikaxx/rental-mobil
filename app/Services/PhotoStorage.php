<?php

namespace App\Services;

use App\Models\Inspection;
use Illuminate\Http\UploadedFile;

class PhotoStorage
{
    /**
     * Store validated inspection photos on the public disk and attach them to
     * the inspection record. Files get random hashed names (no user input).
     *
     * @param  array<int, mixed>  $files
     */
    public function store(Inspection $inspection, array $files): void
    {
        foreach (array_filter($files) as $file) {
            if (! $file instanceof UploadedFile || ! $file->isValid()) {
                continue;
            }

            $inspection->photos()->create([
                'path' => $file->store('inspections', 'public'),
            ]);
        }
    }
}
