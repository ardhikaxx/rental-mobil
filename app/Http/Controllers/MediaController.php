<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\InspectionPhoto;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    /**
     * Serve inspection photos to authenticated users only.
     */
    public function inspectionPhoto(InspectionPhoto $photo): Response
    {
        $filename = basename($photo->path);
        $path = storage_path('uploads/inspections/'.$filename);
        if (! File::exists($path)) {
            $path = storage_path('uploads/'.ltrim($photo->path, '/\\'));
        }

        abort_unless(File::exists($path), 404);

        $file = File::get($path);
        $type = File::mimeType($path);
        $lastModified = File::lastModified($path);

        return response($file, 200)
            ->header('Content-Type', $type)
            ->header('Cache-Control', 'private, max-age=86400')
            ->header('Last-Modified', gmdate('D, d M Y H:i:s', $lastModified).' GMT')
            ->header('ETag', md5($file));
    }

    /**
     * Serve customer KTP document photo securely.
     */
    public function customerKtp(Customer $customer): Response
    {
        abort_unless($customer->ktp_photo, 404);

        $filename = basename($customer->ktp_photo);
        $path = storage_path('uploads/customers/ktp/'.$filename);
        if (! File::exists($path)) {
            $path = storage_path('uploads/'.ltrim($customer->ktp_photo, '/\\'));
        }

        abort_unless(File::exists($path), 404);

        $file = File::get($path);
        $type = File::mimeType($path);
        $lastModified = File::lastModified($path);

        return response($file, 200)
            ->header('Content-Type', $type)
            ->header('Cache-Control', 'private, max-age=86400')
            ->header('Last-Modified', gmdate('D, d M Y H:i:s', $lastModified).' GMT')
            ->header('ETag', md5($file));
    }

    /**
     * Serve customer SIM document photo securely.
     */
    public function customerSim(Customer $customer): Response
    {
        abort_unless($customer->sim_photo, 404);

        $filename = basename($customer->sim_photo);
        $path = storage_path('uploads/customers/sim/'.$filename);
        if (! File::exists($path)) {
            $path = storage_path('uploads/'.ltrim($customer->sim_photo, '/\\'));
        }

        abort_unless(File::exists($path), 404);

        $file = File::get($path);
        $type = File::mimeType($path);
        $lastModified = File::lastModified($path);

        return response($file, 200)
            ->header('Content-Type', $type)
            ->header('Cache-Control', 'private, max-age=86400')
            ->header('Last-Modified', gmdate('D, d M Y H:i:s', $lastModified).' GMT')
            ->header('ETag', md5($file));
    }
}
