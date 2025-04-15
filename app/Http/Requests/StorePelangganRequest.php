<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePelangganRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:20',
            'email' => 'required|email|unique:pelanggans,email',
            'no_ktp' => 'required|string|size:16|unique:pelanggans,no_ktp',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'catatan' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'no_ktp.size' => 'Nomor KTP harus 16 digit',
            'foto_ktp.max' => 'Foto KTP maksimal 2MB'
        ];
    }
}