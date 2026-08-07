<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratKeluarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_surat' => ['required', 'date'],
            'instansi_id' => ['required', 'exists:instansis,id'],
            'kategori_surat_id' => ['required', 'exists:kategori_surats,id'],
            'perihal' => ['required', 'string', 'max:255'],
            'ringkasan' => ['nullable', 'string'],
            'lampiran_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'status' => ['required', 'in:draft,dikirim,diarsipkan'],
            'ditandatangani_oleh' => ['nullable', 'exists:users,id'],
        ];
    }
}
