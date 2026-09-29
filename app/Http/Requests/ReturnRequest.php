<?php

namespace App\Http\Requests;

use App\Enums\Completeness;
use App\Enums\ConditionLevel;
use App\Enums\FuelLevel;
use App\Enums\TireCondition;
use App\Enums\VehicleStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ReturnRequest extends FormRequest
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
            'actual_return_at' => ['required', 'date'],
            'odometer' => ['required', 'integer', 'min:0'],
            'fuel_level' => ['required', Rule::in(array_keys(FuelLevel::options()))],
            'exterior_condition' => ['required', Rule::in(array_keys(ConditionLevel::options()))],
            'interior_condition' => ['required', Rule::in(array_keys(ConditionLevel::options()))],
            'tire_condition' => ['required', Rule::in(array_keys(TireCondition::options()))],
            'completeness' => ['required', Rule::in(array_keys(Completeness::options()))],
            'missing_items' => ['nullable', 'string', 'max:1000'],
            'new_damage' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'next_status' => ['required', Rule::in([
                VehicleStatus::Available->value,
                VehicleStatus::Cleaning->value,
                VehicleStatus::Maintenance->value,
            ])],
            'problem_description' => ['nullable', 'string', 'max:2000'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['file', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('actual_return_at') !== null) {
            $this->merge([
                'actual_return_at' => str_replace('T', ' ', (string) $this->input('actual_return_at')),
            ]);
        }
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'actual_return_at' => 'waktu pengembalian aktual',
            'odometer' => 'odometer akhir',
            'fuel_level' => 'bahan bakar akhir',
            'exterior_condition' => 'kondisi eksterior',
            'interior_condition' => 'kondisi interior',
            'tire_condition' => 'kondisi ban',
            'completeness' => 'kelengkapan kendaraan',
            'missing_items' => 'barang yang kurang',
            'new_damage' => 'kerusakan baru',
            'notes' => 'catatan pemeriksaan',
            'next_status' => 'kondisi akhir kendaraan',
            'problem_description' => 'deskripsi masalah',
            'photos' => 'dokumentasi foto',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $transaction = $this->route('transaction');

            if ($transaction === null) {
                return;
            }

            $returnAt = $this->input('actual_return_at');

            if ($returnAt !== null && $transaction->handover_at !== null) {
                try {
                    if (Carbon::parse($returnAt)->lt($transaction->handover_at)) {
                        $validator->errors()->add('actual_return_at', 'Waktu pengembalian tidak boleh sebelum waktu serah terima ('.tanggal_waktu($transaction->handover_at).').');
                    }
                } catch (\Throwable) {
                    // handled by the date rule
                }
            }

            if ($this->input('odometer') !== null && $transaction->handoverInspection?->odometer !== null) {
                if ((int) $this->input('odometer') < (int) $transaction->handoverInspection->odometer) {
                    $validator->errors()->add('odometer', 'Odometer akhir tidak boleh kurang dari odometer saat serah terima ('.number_format((int) $transaction->handoverInspection->odometer).' km).');
                }
            }

            if ($this->input('completeness') === 'kurang' && trim((string) $this->input('missing_items')) === '') {
                $validator->errors()->add('missing_items', 'Sebutkan barang yang tidak lengkap.');
            }

            if ($this->input('next_status') === VehicleStatus::Maintenance->value && trim((string) $this->input('problem_description')) === '') {
                $validator->errors()->add('problem_description', 'Jelaskan masalah kendaraan karena kendaraan akan diarahkan ke perawatan.');
            }
        });
    }
}
