<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisposisiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'surat_masuk_id' => ['required', 'exists:surat_masuks,id'],
            'kepada_user_id' => ['required', 'exists:users,id'],
            'instruksi' => ['required', 'string'],
            'catatan' => ['nullable', 'string'],
            'batas_waktu' => ['nullable', 'date'],
            'status' => ['required', 'in:menunggu,diproses,selesai'],
        ];
    }
}
