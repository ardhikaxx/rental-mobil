<?php

namespace App\Http\Requests;

use App\Enums\BookingSource;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Services\DriverAvailabilityService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class UpdateTransactionRequest extends FormRequest
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
            'booking_source' => ['required', Rule::in(array_keys(BookingSource::options()))],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'with_driver' => ['nullable', 'boolean'],
            'driver_id' => [
                'nullable', 'required_if:with_driver,1,true', 'integer',
                Rule::exists('drivers', 'id')->where('is_active', true),
            ],
            'deposit_type' => ['nullable', 'string', 'max:50'],
            'deposit_amount' => ['nullable', 'integer', 'min:0'],
            'deposit_notes' => ['nullable', 'string', 'max:1000'],
            'discount' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $normalize = fn (?string $value) => $value === null ? null : str_replace('T', ' ', $value);

        $this->merge([
            'start_at' => $normalize($this->input('start_at')),
            'end_at' => $normalize($this->input('end_at')),
            'with_driver' => $this->boolean('with_driver'),
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'booking_source' => 'sumber booking',
            'start_at' => 'waktu mulai rental',
            'end_at' => 'rencana pengembalian',
            'with_driver' => 'opsi dengan supir',
            'driver_id' => 'supir / driver',
            'deposit_type' => 'jenis jaminan',
            'deposit_amount' => 'nominal uang jaminan',
            'deposit_notes' => 'catatan jaminan',
            'discount' => 'diskon',
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

            $transaction = $this->route('transaction');
            $vehicle = $transaction?->vehicle ?? Vehicle::find($transaction?->vehicle_id);

            if ($vehicle === null) {
                return;
            }

            $days = (int) max(1, ceil($startAt->diffInHours($endAt) / 24));
            $subtotal = $days * (int) $vehicle->daily_rate;

            if ($this->boolean('with_driver') && $this->input('driver_id')) {
                $driver = Driver::find($this->input('driver_id'));
                if ($driver) {
                    $subtotal += ($days * (int) $driver->daily_rate);

                    try {
                        app(DriverAvailabilityService::class)->assertAvailable(
                            $driver,
                            $startAt,
                            $endAt,
                            $this->route('transaction')?->id,
                        );
                    } catch (ValidationException $e) {
                        foreach ($e->errors() as $key => $messages) {
                            foreach ($messages as $msg) {
                                $validator->errors()->add($key, $msg);
                            }
                        }
                    }
                }
            }

            $discount = (int) $this->input('discount', 0);

            if ($discount > $subtotal) {
                $validator->errors()->add('discount', 'Diskon tidak boleh melebihi subtotal rental ('.rupiah($subtotal).').');
            }
        });
    }
}
