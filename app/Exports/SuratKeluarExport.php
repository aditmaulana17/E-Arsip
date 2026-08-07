<?php

namespace App\Exports;

use App\Models\SuratKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuratKeluarExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private array $filters = []) {}

    public function collection()
    {
        return SuratKeluar::with(['instansi', 'kategori'])->filter($this->filters)->latest('tanggal_surat')->get();
    }

    public function headings(): array
    {
        return ['Nomor Surat', 'Tanggal Surat', 'Tujuan Instansi', 'Kategori', 'Perihal', 'Status'];
    }

    public function map($surat): array
    {
        return [
            $surat->nomor_surat,
            $surat->tanggal_surat->format('d-m-Y'),
            $surat->instansi->nama_instansi,
            $surat->kategori->nama_kategori,
            $surat->perihal,
            ucfirst($surat->status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
