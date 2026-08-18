<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor_surat' => ['required', 'string', 'max:100'],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_terima' => ['required', 'date'],
            'instansi_id' => ['required', 'exists:instansis,id'],
            'kategori_surat_id' => ['required', 'exists:kategori_surats,id'],
            'perihal' => ['required', 'string', 'max:255'],
            'ringkasan' => ['nullable', 'string'],
            // Diperbarui menjadi 10240 KB (10MB) agar sinkron dengan PRD E-Arsip
            'lampiran_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'status' => ['required', 'in:baru,diproses,didisposisikan,selesai,diarsipkan'],
            'lokasi_arsip_fisik' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'lampiran_file.mimes' => 'Lampiran file wajib berupa format PDF, JPG, JPEG, atau PNG.',
            'lampiran_file.max' => 'Ukuran lampiran file tidak boleh melebihi 10MB.',
        ];
    }
}