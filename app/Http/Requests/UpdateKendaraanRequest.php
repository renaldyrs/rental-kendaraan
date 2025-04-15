<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKendaraanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'merk' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'warna' => 'required|string|max:30',
            'tahun' => 'required|integer|min:1990|max:'.(date('Y')+1),
            'nomor_plat' => 'required|string|max:15|unique:kendaraans,nomor_plat,'.$this->kendaraan->id,
            'bahan_bakar' => 'required|string|max:20',
            'kapasitas_penumpang' => 'required|integer|min:1',
            'transmisi' => 'required|in:manual,automatic',
            'kategori' => 'required|in:mobil,motor,truk,bus',
            'tarif_sewa' => 'required|numeric|min:10000',
            'status' => 'required|in:tersedia,disewa,perbaikan',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'nullable|string',
            'fasilitas' => 'nullable|string'
        ];
    }
}