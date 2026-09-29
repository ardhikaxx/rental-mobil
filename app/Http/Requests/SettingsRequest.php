<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
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
            'company_name' => ['required', 'string', 'max:120'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:32'],
            'transaction_prefix' => ['required', 'string', 'alpha_dash', 'max:10'],
            'payment_prefix' => ['required', 'string', 'alpha_dash', 'max:10'],
            'min_dp_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'late_fee_grace_minutes' => ['required', 'integer', 'min:0', 'max:10080'],
            'late_fee_mode' => ['required', 'in:per_hour,per_day'],
            'late_fee_rate' => ['required', 'numeric', 'min:0', 'max:100000000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'company_name' => 'nama rental',
            'company_address' => 'alamat rental',
            'company_phone' => 'telepon rental',
            'transaction_prefix' => 'prefix nomor transaksi',
            'payment_prefix' => 'prefix nomor pembayaran',
            'min_dp_percent' => 'persentase DP minimum',
            'late_fee_grace_minutes' => 'masa tenggang denda',
            'late_fee_mode' => 'mode perhitungan denda',
            'late_fee_rate' => 'tarif denda',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'late_fee_mode.in' => 'Mode perhitungan denda harus per_hour atau per_day.',
        ];
    }
}
