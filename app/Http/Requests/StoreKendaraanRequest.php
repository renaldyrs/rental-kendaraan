<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKendaraanRequest extends FormRequest
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
            'nomor_plat' => 'required|string|max:15|unique:kendaraans,nomor_plat',
            'bahan_bakar' => 'required|string|max:20',
            'kapasitas_penumpang' => 'required|integer|min:1',
            'transmisi' => 'required|in:manual,automatic',
            'kategori' => 'required|in:mobil,motor,truk,bus',
            'tarif_sewa' => 'required|numeric|min:10000',
            'status' => 'required|in:tersedia,disewa,perbaikan',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'nullable|string',
            'fasilitas' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'tahun.max' => 'Tahun tidak boleh lebih dari tahun depan',
            'gambar.max' => 'Gambar maksimal 2MB',
            'tarif_sewa.min' => 'Tarif sewa minimal Rp10.000'
        ];
    }
}