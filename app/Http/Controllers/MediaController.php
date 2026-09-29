<?php

namespace App\Http\Controllers;

use App\Models\InspectionPhoto;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Serve inspection photos to authenticated users only.
     */
    public function inspectionPhoto(InspectionPhoto $photo): Response
    {
        abort_unless(Storage::disk('public')->exists($photo->path), 404);

        return response()->file(Storage::disk('public')->path($photo->path));
    }
}
