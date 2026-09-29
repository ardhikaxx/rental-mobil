<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionPhoto extends Model
{
    protected $fillable = [
        'inspection_id',
        'path',
    ];

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function getPhotoUrlAttribute(): string
    {
        $filename = basename($this->path);

        return url('uploads/inspections/'.$filename);
    }
}
