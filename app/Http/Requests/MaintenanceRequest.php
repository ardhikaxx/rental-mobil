<?php

namespace App\Http\Requests;

use App\Enums\MaintenanceStatus;
use App\Enums\MaintenanceType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class MaintenanceRequest extends FormRequest
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
        $maintenance = $this->route('maintenance');

        return [
            'vehicle_id' => [
                'required', 'integer',
                Rule::exists('vehicles', 'id')->whereNull('deleted_at'),
            ],
            'type' => ['required', Rule::in(array_keys(MaintenanceType::options()))],
            'status' => ['nullable', Rule::in(array_keys(MaintenanceStatus::options()))],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'odometer' => ['nullable', 'integer', 'min:0'],
            'description' => ['required', 'string', 'max:2000'],
            'cost' => ['nullable', 'integer', 'min:0', 'max:1000000000'],
            'workshop' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'vehicle_id' => 'kendaraan',
            'type' => 'jenis perawatan',
            'status' => 'status perawatan',
            'start_date' => 'tanggal mulai',
            'end_date' => 'tanggal selesai',
            'odometer' => 'kilometer',
            'description' => 'deskripsi masalah',
            'cost' => 'biaya perawatan',
            'workshop' => 'bengkel',
            'notes' => 'catatan',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $maintenance = $this->route('maintenance');
            $status = $this->input('status');

            if ($maintenance === null || $status === null) {
                return;
            }

            if ($maintenance->status === MaintenanceStatus::Completed && $status !== MaintenanceStatus::Completed->value) {
                $validator->errors()->add('status', 'Perawatan yang sudah selesai tidak dapat diubah statusnya kembali.');
            }
        });
    }
}
