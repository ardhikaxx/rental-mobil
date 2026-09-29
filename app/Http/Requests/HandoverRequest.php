<?php

namespace App\Http\Requests;

use App\Enums\Completeness;
use App\Enums\ConditionLevel;
use App\Enums\FuelLevel;
use App\Enums\TireCondition;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class HandoverRequest extends FormRequest
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
            'handover_at' => ['required', 'date'],
            'odometer' => ['required', 'integer', 'min:0'],
            'fuel_level' => ['required', Rule::in(array_keys(FuelLevel::options()))],
            'exterior_condition' => ['required', Rule::in(array_keys(ConditionLevel::options()))],
            'interior_condition' => ['required', Rule::in(array_keys(ConditionLevel::options()))],
            'tire_condition' => ['required', Rule::in(array_keys(TireCondition::options()))],
            'completeness' => ['required', Rule::in(array_keys(Completeness::options()))],
            'missing_items' => ['nullable', 'string', 'max:1000'],
            'existing_damage' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['file', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('handover_at') !== null) {
            $this->merge([
                'handover_at' => str_replace('T', ' ', (string) $this->input('handover_at')),
            ]);
        }
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'handover_at' => 'waktu serah terima',
            'odometer' => 'odometer awal',
            'fuel_level' => 'bahan bakar awal',
            'exterior_condition' => 'kondisi eksterior',
            'interior_condition' => 'kondisi interior',
            'tire_condition' => 'kondisi ban',
            'completeness' => 'kelengkapan kendaraan',
            'missing_items' => 'barang yang kurang',
            'existing_damage' => 'catatan kerusakan lama',
            'notes' => 'catatan pemeriksaan',
            'photos' => 'dokumentasi foto',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $transaction = $this->route('transaction');

            if ($transaction === null || $this->input('odometer') === null) {
                return;
            }

            $vehicle = $transaction->vehicle;

            if ($vehicle !== null && (int) $this->input('odometer') < (int) $vehicle->odometer) {
                $validator->errors()->add('odometer', 'Odometer awal tidak boleh kurang dari kilometer terakhir kendaraan ('.number_format((int) $vehicle->odometer).' km).');
            }

            if ($this->input('completeness') === 'kurang' && trim((string) $this->input('missing_items')) === '') {
                $validator->errors()->add('missing_items', 'Sebutkan barang yang tidak lengkap.');
            }
        });
    }
}
