<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransaksiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Pastikan ini true jika otorisasi tidak diperlukan
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama_customer' => 'required|string|max:255',
            'email_customer' => 'required|email|max:255',
            'notelp_customer' => 'required|string|max:15',
            'alamat_customer' => 'required|string|max:255',
            'plus_services.*' => 'exists:plus_services,id', // Validasi layanan tambahan
            'promosi_kode' => 'nullable|string|exists:promosis,kode', // Kode promosi opsional
            'status' => 'required|in:downpayment,paid',
            'category_hargas.*.id' => 'required|exists:categories,id', // Validasi kategori harga
            'category_hargas.*.qty' => 'required|integer|min:1', // Validasi jumlah kategori
            'downpayment_amount' => 'nullable|numeric|min:0', // Validasi minimal downpayment
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'nama_customer.required' => 'Nama customer wajib diisi.',
            'email_customer.required' => 'Email customer wajib diisi.',
            'email_customer.email' => 'Format email tidak valid.',
            'notelp_customer.required' => 'Nomor telepon wajib diisi.',
            'alamat_customer.required' => 'Alamat wajib diisi.',
            'status.required' => 'Status pembayaran harus dipilih.',
            'plus_services.*.exists' => 'Layanan tambahan tidak valid.',
            'promosi_kode.exists' => 'Kode promosi tidak ditemukan.',
            'category_hargas.*.id.required' => 'Kategori harga wajib diisi.',
            'category_hargas.*.qty.required' => 'Jumlah kategori harus diisi.',
            'category_hargas.*.qty.min' => 'Jumlah kategori minimal 1.',
            'downpayment_amount.numeric' => 'Jumlah downpayment harus berupa angka.',
            'downpayment_amount.min' => 'Jumlah downpayment tidak boleh kurang dari 0.',
        ];
    }
}
