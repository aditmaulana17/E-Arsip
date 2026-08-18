<?php

namespace App\Exports;

use App\Models\SuratMasuk;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class SuratMasukExport implements FromCollection
{
    /** @var array */
    protected $request;

    public function __construct(array $request = [])
    {
        $this->request = $request;
    }

    public function collection(): Collection
    {
        return SuratMasuk::with(['instansi', 'kategori'])
            ->filter($this->request)
            ->latest('tanggal_terima')
            ->get();
    }
}