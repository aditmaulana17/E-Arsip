<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratMasuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_agenda',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_terima',
        'instansi_id',
        'kategori_surat_id',
        'perihal',
        'ringkasan',
        'lampiran_file',
        'status',
        'lokasi_arsip_fisik',
        'diterima_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'tanggal_terima' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi Model
    |--------------------------------------------------------------------------
    */

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSurat::class, 'kategori_surat_id');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diterima_oleh');
    }

    public function disposisi(): HasMany
    {
        return $this->hasMany(Disposisi::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper / Method Bisnis
    |--------------------------------------------------------------------------
    */

    /**
     * Generate nomor agenda otomatis secara anti-bentrok.
     * Format: AG/0001/08/2026
     */
    public static function generateNomorAgenda(): string
    {
        $bulan = str_pad((string) now()->month, 2, '0', STR_PAD_LEFT);
        $tahun = now()->year;

        // Ambil data terakhir pada tahun DAN bulan berjalan (termasuk yang soft delete)
        $lastSurat = static::withTrashed()
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->orderBy('id', 'desc')
            ->first();

        $urutan = 1;
        if ($lastSurat && $lastSurat->nomor_agenda) {
            $parts = explode('/', $lastSurat->nomor_agenda);
            if (isset($parts[1]) && is_numeric($parts[1])) {
                $urutan = ((int) $parts[1]) + 1;
            }
        }

        // Looping cek fisik ke database agar tidak duplicate
        do {
            $nomorAgenda = sprintf('AG/%04d/%s/%d', $urutan, $bulan, $tahun);
            $exists = static::withTrashed()->where('nomor_agenda', $nomorAgenda)->exists();
            if ($exists) {
                $urutan++;
            }
        } while ($exists);

        return $nomorAgenda;
    }

    /*
    |--------------------------------------------------------------------------
    | Local Scope Filter
    |--------------------------------------------------------------------------
    */

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, fn (Builder $q, string $v) => $q->where(function (Builder $q2) use ($v) {
                $q2->where('perihal', 'like', "%{$v}%")
                    ->orWhere('nomor_surat', 'like', "%{$v}%")
                    ->orWhere('nomor_agenda', 'like', "%{$v}%");
            }))
            ->when($filters['kategori_id'] ?? null, fn (Builder $q, $v) => $q->where('kategori_surat_id', $v))
            ->when($filters['instansi_id'] ?? null, fn (Builder $q, $v) => $q->where('instansi_id', $v))
            ->when($filters['status'] ?? null, fn (Builder $q, $v) => $q->where('status', $v))
            ->when($filters['dari_tanggal'] ?? null, fn (Builder $q, $v) => $q->whereDate('tanggal_terima', '>=', $v))
            ->when($filters['sampai_tanggal'] ?? null, fn (Builder $q, $v) => $q->whereDate('tanggal_terima', '<=', $v));
    }
}