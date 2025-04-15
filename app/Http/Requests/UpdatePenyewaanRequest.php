<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePenyewaanRequest extends FormRequest
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
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'metode_pembayaran' => 'required|in:transfer,tunai,kredit',
            'catatan' => 'nullable|string|max:500'
        ];
    }
}