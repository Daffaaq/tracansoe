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

            // Validasi untuk `category_sepatu` dan `category_hargas`
            'category_sepatu' => 'required|array',
            'category_sepatu.*.id' => 'required|exists:category_sepatus,id',
            'category_sepatu.*.category_hargas' => 'required_with:category_sepatu.*.id|array', // Required when main category is selected
            'category_sepatu.*.category_hargas.*.id' => 'required|exists:categories,id', // Ensure each selected subcategory has an ID
            'category_sepatu.*.category_hargas.*.qty' => 'required_with:category_sepatu.*.category_hargas.*.id|integer|min:1', // Require qty if subcategory id exists


            // Validasi untuk `plus_services`
            'plus_services' => 'nullable|array', // Jadikan nullable array
            'plus_services.*.id' => 'nullable|exists:plus_services,id', // nullable jika kosong
            'plus_services.*.category_sepatu_id' => 'nullable|exists:category_sepatus,id', // nullable jika kosong


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
            'downpayment_amount.numeric' => 'Jumlah downpayment harus berupa angka.',
            'downpayment_amount.min' => 'Jumlah downpayment tidak boleh kurang dari 0.',

            // Custom messages for category_sepatu and category_hargas
            'category_sepatu.required' => 'Pilih setidaknya satu kategori sepatu.',
            'category_sepatu.*.category_hargas.required' => 'Setiap kategori sepatu harus memiliki setidaknya satu subkategori (kategori harga).',
            'category_sepatu.*.category_hargas.*.id.required' => 'Kategori harga wajib diisi.',
            'category_sepatu.*.category_hargas.*.id.exists' => 'Kategori harga tidak valid.',
            'category_sepatu.*.category_hargas.*.qty.required' => 'Jumlah kategori harus diisi.',
            'category_sepatu.*.category_hargas.*.qty.integer' => 'Jumlah kategori harus berupa angka.',
            'category_sepatu.*.category_hargas.*.qty.min' => 'Jumlah kategori minimal 1.',

            // Custom messages for plus_services
            'plus_services.*.category_sepatu_id.required' => 'Pilih kategori sepatu untuk layanan tambahan.',
            'plus_services.*.category_sepatu_id.exists' => 'Kategori sepatu untuk layanan tambahan tidak valid.',
            'plus_services.*.id.required' => 'Pilih layanan tambahan yang valid.',
            'plus_services.*.id.exists' => 'Layanan tambahan tidak valid.',
        ];
    }
}
