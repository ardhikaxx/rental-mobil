<?php

namespace App\Http\Requests;

use App\Enums\BookingSource;
use App\Enums\PaymentMethod;
use App\Models\Vehicle;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreTransactionRequest extends FormRequest
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
            'customer_id' => [
                'required', 'integer',
                Rule::exists('customers', 'id')->whereNull('deleted_at'),
            ],
            'vehicle_id' => [
                'required', 'integer',
                Rule::exists('vehicles', 'id')->whereNull('deleted_at'),
            ],
            'booking_source' => ['required', Rule::in(array_keys(BookingSource::options()))],
            'start_at' => ['required', 'date', 'after:now'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'discount' => ['nullable', 'integer', 'min:0'],
            'dp_amount' => ['nullable', 'integer', 'min:0'],
            'dp_method' => ['nullable', Rule::in(array_keys(PaymentMethod::options()))],
            'notes' => ['nullable', 'string', 'max:2000'],
            'save_as_draft' => ['nullable', 'in:0,1'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $normalize = fn (?string $value) => $value === null ? null : str_replace('T', ' ', $value);

        $this->merge([
            'start_at' => $normalize($this->input('start_at')),
            'end_at' => $normalize($this->input('end_at')),
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'customer_id' => 'pelanggan',
            'vehicle_id' => 'kendaraan',
            'booking_source' => 'sumber booking',
            'start_at' => 'waktu mulai rental',
            'end_at' => 'rencana pengembalian',
            'discount' => 'diskon',
            'dp_amount' => 'uang muka',
            'notes' => 'catatan',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $start = $this->input('start_at');
            $end = $this->input('end_at');

            if ($start === null || $end === null) {
                return;
            }

            try {
                $startAt = Carbon::parse($start);
                $endAt = Carbon::parse($end);
            } catch (\Throwable) {
                return;
            }

            $vehicle = Vehicle::find($this->input('vehicle_id'));

            if ($vehicle === null) {
                return;
            }

            $days = (int) max(1, ceil($startAt->diffInHours($endAt) / 24));
            $subtotal = $days * (int) $vehicle->daily_rate;
            $discount = (int) $this->input('discount', 0);
            $dp = (int) $this->input('dp_amount', 0);
            $total = max(0, $subtotal - $discount);

            if ($discount > $subtotal) {
                $validator->errors()->add('discount', 'Diskon tidak boleh melebihi subtotal rental ('.rupiah($subtotal).').');
            }

            if ($dp > $total) {
                $validator->errors()->add('dp_amount', 'Uang muka tidak boleh melebihi total rental ('.rupiah($total).').');
            }
        });
    }
}
