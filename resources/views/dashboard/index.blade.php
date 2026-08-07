@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
        <p class="text-slate-400 text-sm">Total Surat Masuk</p>
        <p class="text-3xl font-bold text-slate-800">{{ $totalSuratMasuk }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-emerald-500">
        <p class="text-slate-400 text-sm">Total Surat Keluar</p>
        <p class="text-3xl font-bold text-slate-800">{{ $totalSuratKeluar }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-amber-500">
        <p class="text-slate-400 text-sm">Surat Belum Diproses</p>
        <p class="text-3xl font-bold text-slate-800">{{ $suratBelumDiproses }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-red-500">
        <p class="text-slate-400 text-sm">Disposisi Menunggu Anda</p>
        <p class="text-3xl font-bold text-slate-800">{{ $disposisiMenunggu }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-slate-700 mb-4">Statistik Surat 12 Bulan Terakhir</h3>
        <canvas id="chartSurat" height="110"></canvas>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-slate-700 mb-4">Disposisi Untuk Saya</h3>
        <div class="space-y-3">
            @forelse($disposisiSaya as $d)
                <div class="border border-slate-100 rounded-lg p-3">
                    <p class="text-sm font-medium text-slate-700">{{ $d->suratMasuk->perihal }}</p>
                    <p class="text-xs text-slate-400">Dari {{ $d->dari->name }} &middot; {{ $d->created_at->diffForHumans() }}</p>
                    <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-600">{{ ucfirst($d->status) }}</span>
                </div>
            @empty
                <p class="text-sm text-slate-400">Tidak ada disposisi menunggu.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 mt-6">
    <h3 class="font-semibold text-slate-700 mb-4">Surat Masuk Terbaru</h3>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-slate-400 border-b">
                <th class="py-2">Nomor Agenda</th>
                <th>Perihal</th>
                <th>Instansi</th>
                <th>Kategori</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suratTerbaru as $s)
            <tr class="border-b last:border-0">
                <td class="py-2 font-medium">{{ $s->nomor_agenda }}</td>
                <td>{{ $s->perihal }}</td>
                <td>{{ $s->instansi->nama_instansi }}</td>
                <td>{{ $s->kategori->nama_kategori }}</td>
                <td><span class="text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-600">{{ ucfirst($s->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-4 text-center text-slate-400">Belum ada data surat masuk.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
const labels = {!! json_encode(array_keys($statistikMasuk->toArray())) !!};
new Chart(document.getElementById('chartSurat'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            { label: 'Surat Masuk', data: {!! json_encode(array_values($statistikMasuk->toArray())) !!}, borderColor: '#3457d5', backgroundColor: 'rgba(52,87,213,0.1)', tension: 0.3, fill: true },
            { label: 'Surat Keluar', data: {!! json_encode(array_values($statistikKeluar->toArray())) !!}, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', tension: 0.3, fill: true },
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>
@endsection
