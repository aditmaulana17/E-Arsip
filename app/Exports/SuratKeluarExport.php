<?php

namespace App\Exports;

use App\Models\SuratKeluar;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class SuratKeluarExport implements FromCollection
{
    /** @var array */
    protected array $request;

    public function __construct(array $request = [])
    {
        $this->request = $request;
    }

    public function collection(): Collection
    {
        return SuratKeluar::with(['instansi', 'kategori'])
            ->filter($this->request)
            ->latest('tanggal_surat')
            ->get();
    }
}