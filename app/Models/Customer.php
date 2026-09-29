<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'id_number',
        'sim_number',
        'phone',
        'email',
        'address',
        'birth_date',
        'notes',
        'ktp_photo',
        'sim_photo',
        'verification_status',
        'verified_at',
        'verified_by',
        'rejection_reason',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if ($term === null || trim($term) === '') {
            return $query;
        }

        $term = trim($term);

        return $query->where(function (Builder $builder) use ($term) {
            $builder->where('name', 'like', "%{$term}%")
                ->orWhere('id_number', 'like', "%{$term}%")
                ->orWhere('sim_number', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    public function getKtpPhotoUrlAttribute(): ?string
    {
        if (! $this->ktp_photo) {
            return null;
        }

        $filename = basename($this->ktp_photo);

        return url('uploads/customers/ktp/'.$filename);
    }

    public function getSimPhotoUrlAttribute(): ?string
    {
        if (! $this->sim_photo) {
            return null;
        }

        $filename = basename($this->sim_photo);

        return url('uploads/customers/sim/'.$filename);
    }
}
