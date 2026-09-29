<?php

namespace App\Models;

use App\Enums\FuelLevel;
use App\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'brand',
        'model',
        'type',
        'year',
        'color',
        'license_plate',
        'chassis_number',
        'engine_number',
        'daily_rate',
        'photo',
        'status',
        'odometer',
        'fuel_level',
        'notes',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'daily_rate' => 'integer',
            'odometer' => 'integer',
            'year' => 'integer',
            'status' => VehicleStatus::class,
            'fuel_level' => FuelLevel::class,
            'is_active' => 'boolean',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if ($term === null || trim($term) === '') {
            return $query;
        }

        $term = trim($term);

        return $query->where(function (Builder $builder) use ($term) {
            $builder->where('code', 'like', "%{$term}%")
                ->orWhere('license_plate', 'like', "%{$term}%")
                ->orWhere('brand', 'like', "%{$term}%")
                ->orWhere('model', 'like', "%{$term}%");
        });
    }

    public function displayName(): string
    {
        return "{$this->brand} {$this->model}";
    }

    public function isRentable(): bool
    {
        return $this->is_active && $this->status !== VehicleStatus::Rented;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        $filename = basename($this->photo);

        return url('uploads/vehicles/'.$filename);
    }
}
