<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
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
        $customer = $this->route('customer');

        return [
            'name' => ['required', 'string', 'max:120'],
            'id_number' => [
                'required', 'string', 'max:32', 'regex:/^[0-9A-Za-z]{5,32}$/',
                Rule::unique('customers', 'id_number')->ignore($customer?->id),
            ],
            'sim_number' => ['nullable', 'string', 'max:50'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'email' => ['nullable', 'email', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'ktp_photo' => ['nullable', 'image', 'max:5120'],
            'delete_ktp_photo' => ['nullable', 'boolean'],
            'sim_photo' => ['nullable', 'image', 'max:5120'],
            'delete_sim_photo' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama lengkap',
            'id_number' => 'nomor identitas',
            'sim_number' => 'nomor SIM',
            'phone' => 'nomor telepon',
            'email' => 'email',
            'address' => 'alamat',
            'birth_date' => 'tanggal lahir',
            'notes' => 'catatan',
            'ktp_photo' => 'foto KTP',
            'sim_photo' => 'foto SIM',
        ];
    }

    public function messages(): array
    {
        return [
            'id_number.regex' => 'Nomor identitas hanya boleh berisi angka atau huruf (5-32 karakter).',
            'phone.regex' => 'Nomor telepon tidak valid. Gunakan format angka, misalnya 081234567890.',
        ];
    }
}
