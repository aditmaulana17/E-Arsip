<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratMasuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_agenda', 'nomor_surat', 'tanggal_surat', 'tanggal_terima',
        'instansi_id', 'kategori_surat_id', 'perihal', 'ringkasan',
        'lampiran_file', 'status', 'lokasi_arsip_fisik', 'diterima_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'tanggal_terima' => 'date',
        ];
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriSurat::class, 'kategori_surat_id');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'diterima_oleh');
    }

    public function disposisi()
    {
        return $this->hasMany(Disposisi::class);
    }

    // Generate nomor agenda otomatis: AG/0001/07/2026
    public static function generateNomorAgenda(): string
    {
        $bulan = str_pad(now()->month, 2, '0', STR_PAD_LEFT);
        $tahun = now()->year;
        $urutan = self::whereYear('created_at', $tahun)->count() + 1;
        return sprintf('AG/%04d/%s/%d', $urutan, $bulan, $tahun);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['search'] ?? null, fn ($q, $v) => $q->where(function ($q2) use ($v) {
                $q2->where('perihal', 'like', "%{$v}%")
                    ->orWhere('nomor_surat', 'like', "%{$v}%")
                    ->orWhere('nomor_agenda', 'like', "%{$v}%");
            }))
            ->when($filters['kategori_id'] ?? null, fn ($q, $v) => $q->where('kategori_surat_id', $v))
            ->when($filters['instansi_id'] ?? null, fn ($q, $v) => $q->where('instansi_id', $v))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['dari_tanggal'] ?? null, fn ($q, $v) => $q->whereDate('tanggal_terima', '>=', $v))
            ->when($filters['sampai_tanggal'] ?? null, fn ($q, $v) => $q->whereDate('tanggal_terima', '<=', $v));
    }
}
