<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePelangganRequest extends FormRequest
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
            'email' => 'required|email|unique:pelanggans,email,'.$this->pelanggan->id,
            'no_ktp' => 'required|string|size:16|unique:pelanggans,no_ktp,'.$this->pelanggan->id,
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'catatan' => 'nullable|string'
        ];
    }
}