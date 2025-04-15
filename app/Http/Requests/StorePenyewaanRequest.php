<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePenyewaanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pelanggan_id' => [
                'required',
                Rule::exists('pelanggans', 'id')->where('is_verified', true)
            ],
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'metode_pembayaran' => 'required|in:transfer,tunai,kredit',
            'jumlah_pembayaran' => 'nullable|numeric|min:0',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'catatan' => 'nullable|string|max:500'
        ];
    }

    public function messages()
    {
        return [
            'pelanggan_id.exists' => 'Pelanggan belum terverifikasi',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai'
        ];
    }
}