<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_masuk_id', 'dari_user_id', 'kepada_user_id',
        'instruksi', 'catatan', 'batas_waktu', 'status',
    ];

    protected function casts(): array
    {
        return ['batas_waktu' => 'date'];
    }

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    public function dari()
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    public function kepada()
    {
        return $this->belongsTo(User::class, 'kepada_user_id');
    }
}
