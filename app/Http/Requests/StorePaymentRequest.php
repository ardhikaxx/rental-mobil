<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use App\Enums\PaymentType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
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
            'transaction_id' => ['required', 'integer', 'exists:transactions,id'],
            'amount' => ['required', 'integer', 'min:1', 'max:1000000000'],
            'method' => ['required', Rule::in(array_keys(PaymentMethod::options()))],
            'type' => ['required', Rule::in(array_keys(PaymentType::options()))],
            'paid_at' => ['required', 'date', 'before_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'transaction_id' => 'transaksi',
            'amount' => 'nominal pembayaran',
            'method' => 'metode pembayaran',
            'type' => 'jenis pembayaran',
            'paid_at' => 'tanggal pembayaran',
            'notes' => 'catatan',
        ];
    }
}
