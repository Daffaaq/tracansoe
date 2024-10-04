<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pastikan hanya superadmin yang dapat memperbarui kategori
        return auth()->user()->role === 'superadmin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama_kategori' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'price' => 'nullable|numeric',
            'estimation' => 'nullable|integer',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }

    /**
     * Custom error messages (optional)
     */
    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'description.required' => 'Deskripsi kategori wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'estimation.integer' => 'Estimasi harus berupa angka.',
            'parent_id.exists' => 'ID kategori induk tidak ditemukan.',
        ];
    }
}
