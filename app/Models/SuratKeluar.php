<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratKeluar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_surat', 'tanggal_surat', 'instansi_id', 'kategori_surat_id',
        'perihal', 'ringkasan', 'lampiran_file', 'status',
        'dibuat_oleh', 'ditandatangani_oleh',
    ];

    protected function casts(): array
    {
        return ['tanggal_surat' => 'date'];
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriSurat::class, 'kategori_surat_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function penandatangan()
    {
        return $this->belongsTo(User::class, 'ditandatangani_oleh');
    }

    // Format nomor surat keluar: 001/KODE/VII/2026
    public static function generateNomorSurat(string $kodeKategori): string
    {
        $romawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];
        $tahun = now()->year;
        $bulanRomawi = $romawi[now()->month];
        $urutan = self::whereYear('created_at', $tahun)->count() + 1;
        return sprintf('%03d/%s/%s/%d', $urutan, $kodeKategori, $bulanRomawi, $tahun);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['search'] ?? null, fn ($q, $v) => $q->where(function ($q2) use ($v) {
                $q2->where('perihal', 'like', "%{$v}%")->orWhere('nomor_surat', 'like', "%{$v}%");
            }))
            ->when($filters['kategori_id'] ?? null, fn ($q, $v) => $q->where('kategori_surat_id', $v))
            ->when($filters['instansi_id'] ?? null, fn ($q, $v) => $q->where('instansi_id', $v))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['dari_tanggal'] ?? null, fn ($q, $v) => $q->whereDate('tanggal_surat', '>=', $v))
            ->when($filters['sampai_tanggal'] ?? null, fn ($q, $v) => $q->whereDate('tanggal_surat', '<=', $v));
    }
}
