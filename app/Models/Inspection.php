<?php

namespace App\Models;

use App\Enums\ConditionLevel;
use App\Enums\FuelLevel;
use App\Enums\InspectionType;
use App\Enums\TireCondition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'transaction_id',
        'type',
        'inspected_at',
        'inspected_by',
        'odometer',
        'fuel_level',
        'exterior_condition',
        'interior_condition',
        'tire_condition',
        'completeness',
        'missing_items',
        'existing_damage',
        'new_damage',
        'notes',
        'vehicle_status_after',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => InspectionType::class,
            'inspected_at' => 'datetime',
            'odometer' => 'integer',
            'fuel_level' => FuelLevel::class,
            'exterior_condition' => ConditionLevel::class,
            'interior_condition' => ConditionLevel::class,
            'tire_condition' => TireCondition::class,
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(InspectionPhoto::class);
    }
}
