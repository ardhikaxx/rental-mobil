<?php

namespace App\Models;

use App\Enums\MaintenanceStatus;
use App\Enums\MaintenanceType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'type',
        'status',
        'start_date',
        'end_date',
        'odometer',
        'description',
        'cost',
        'workshop',
        'notes',
        'recorded_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => MaintenanceType::class,
            'status' => MaintenanceStatus::class,
            'start_date' => 'date',
            'end_date' => 'date',
            'odometer' => 'integer',
            'cost' => 'integer',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', MaintenanceStatus::active());
    }
}
