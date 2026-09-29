<?php

namespace App\Http\Requests;

use App\Enums\VehicleType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $vehicle = $this->route('vehicle');

        return [
            'code' => [
                'required', 'string', 'max:32',
                Rule::unique('vehicles', 'code')->ignore($vehicle?->id),
            ],
            'brand' => ['required', 'string', 'max:60'],
            'model' => ['required', 'string', 'max:60'],
            'type' => ['required', Rule::in(array_keys(VehicleType::options()))],
            'year' => ['required', 'integer', 'min:1990', 'max:'.(now()->year + 1)],
            'color' => ['required', 'string', 'max:40'],
            'license_plate' => [
                'required', 'string', 'max:20', 'regex:/^[A-Za-z0-9\s\-]{3,20}$/',
                Rule::unique('vehicles', 'license_plate')->ignore($vehicle?->id),
            ],
            'chassis_number' => ['nullable', 'string', 'max:60'],
            'engine_number' => ['nullable', 'string', 'max:60'],
            'daily_rate' => ['required', 'integer', 'min:0', 'max:10000000'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'fuel_level' => ['nullable', Rule::in(['empty', 'quarter', 'half', 'three_quarters', 'full'])],
            'notes' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode unit',
            'brand' => 'merek',
            'model' => 'model',
            'type' => 'tipe kendaraan',
            'year' => 'tahun',
            'color' => 'warna',
            'license_plate' => 'nomor polisi',
            'chassis_number' => 'nomor rangka',
            'engine_number' => 'nomor mesin',
            'daily_rate' => 'tarif sewa harian',
            'photo' => 'foto kendaraan',
            'fuel_level' => 'volume bahan bakar',
            'notes' => 'catatan',
            'is_active' => 'status aktif',
        ];
    }
}
