<?php

namespace App\Http\Requests;

use App\Enums\Completeness;
use App\Enums\ConditionLevel;
use App\Enums\FuelLevel;
use App\Enums\TireCondition;
use App\Models\Vehicle;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreInspectionRequest extends FormRequest
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
        return [
            'vehicle_id' => [
                'required', 'integer',
                Rule::exists('vehicles', 'id')->whereNull('deleted_at'),
            ],
            'inspected_at' => ['required', 'date'],
            'odometer' => ['required', 'integer', 'min:0'],
            'fuel_level' => ['required', Rule::in(array_keys(FuelLevel::options()))],
            'exterior_condition' => ['required', Rule::in(array_keys(ConditionLevel::options()))],
            'interior_condition' => ['required', Rule::in(array_keys(ConditionLevel::options()))],
            'tire_condition' => ['required', Rule::in(array_keys(TireCondition::options()))],
            'completeness' => ['nullable', Rule::in(array_keys(Completeness::options()))],
            'missing_items' => ['nullable', 'string', 'max:1000'],
            'existing_damage' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['file', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('inspected_at') !== null) {
            $this->merge([
                'inspected_at' => str_replace('T', ' ', (string) $this->input('inspected_at')),
            ]);
        }
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'vehicle_id' => 'kendaraan',
            'inspected_at' => 'waktu pemeriksaan',
            'odometer' => 'odometer',
            'fuel_level' => 'bahan bakar',
            'exterior_condition' => 'kondisi eksterior',
            'interior_condition' => 'kondisi interior',
            'tire_condition' => 'kondisi ban',
            'completeness' => 'kelengkapan kendaraan',
            'missing_items' => 'barang yang kurang',
            'existing_damage' => 'catatan kerusakan',
            'notes' => 'catatan pemeriksaan',
            'photos' => 'dokumentasi foto',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->input('odometer') === null) {
                return;
            }

            $vehicle = Vehicle::find($this->input('vehicle_id'));

            if ($vehicle !== null && (int) $this->input('odometer') < (int) $vehicle->odometer) {
                $validator->errors()->add('odometer', 'Odometer tidak boleh kurang dari kilometer terakhir kendaraan ('.number_format((int) $vehicle->odometer).' km).');
            }
        });
    }
}
