<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">

    <!-- WELCOME BANNER -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-500/10">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-xs font-medium text-blue-100 border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistem E-Arsip Aktif
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Selamat Datang Kembali, <?php echo e(auth()->user()->name ?? 'Administrator'); ?>!</h1>
                <p class="text-blue-100/90 text-sm max-w-xl">Kelola arsip surat masuk, surat keluar, dan disposisi dokumen dengan cepat, terstruktur, dan aman.</p>
            </div>
            
            <div class="flex items-center gap-3 shrink-0">
                <a href="<?php echo e(route('surat-masuk.create')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-700 hover:bg-blue-50 rounded-xl text-xs font-semibold shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Surat Masuk</span>
                </a>
                <a href="<?php echo e(route('surat-keluar.create')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-500/30 hover:bg-blue-500/40 text-white border border-white/20 rounded-xl text-xs font-semibold backdrop-blur-md transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Surat Keluar</span>
                </a>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <!-- STATS GRID CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Surat Masuk</span>
                <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-800 tracking-tight"><?php echo e($totalSuratMasuk); ?></span>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">Arsip Masuk</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Surat Keluar</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-800 tracking-tight"><?php echo e($totalSuratKeluar); ?></span>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">Terkirim</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Belum Diproses</span>
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-800 tracking-tight"><?php echo e($suratPending); ?></span>
                <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg">Butuh Tindakan</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Disposisi Saya</span>
                <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-800 tracking-tight"><?php echo e($disposisiMenunggu); ?></span>
                <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2.5 py-1 rounded-lg">Tindak Lanjut</span>
            </div>
        </div>

    </div>

    <!-- MAIN CONTENT: CHART & DISPOSISI WIDGET -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Statistik Surat 12 Bulan Terakhir</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Grafik perbandingan volume surat masuk dan surat keluar.</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-medium shrink-0">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-600"></span> Surat Masuk</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Surat Keluar</span>
                </div>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="suratChart"></canvas>
            </div>
        </div>

        <!-- Disposisi Widget -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h2 class="text-base font-bold text-slate-800">Disposisi Untuk Saya</h2>
                    <a href="<?php echo e(route('disposisi.index')); ?>" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Lihat Semua →</a>
                </div>

                <div class="mt-4 space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $listDisposisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('disposisi.show', $d->id)); ?>" class="block p-3.5 bg-slate-50 hover:bg-blue-50/50 rounded-xl border border-slate-100 transition space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-700 truncate max-w-[180px]"><?php echo e($d->suratMasuk->perihal ?? 'Surat Disposisi'); ?></span>
                                <span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-md font-semibold shrink-0">Menunggu</span>
                            </div>
                            <!-- Menggunakan $d->dari sesuai Model Disposisi -->
                            <p class="text-xs text-slate-500 line-clamp-1">Dari: <?php echo e($d->dari->name ?? '-'); ?></p>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-10 space-y-2">
                            <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            </div>
                            <p class="text-xs text-slate-500 font-medium">Tidak ada tugas disposisi menunggu.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-slate-100">
                <a href="<?php echo e(route('disposisi.index')); ?>" class="w-full py-2.5 text-center block text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Kelola Semua Disposisi
                </a>
            </div>
        </div>

    </div>

    <!-- TABLE SECTION: SURAT MASUK TERBARU -->
    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-800">Surat Masuk Terbaru</h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar arsip surat yang baru diterimakan ke sistem.</p>
            </div>
            <a href="<?php echo e(route('surat-masuk.index')); ?>" class="px-3.5 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                Lihat Selengkapnya
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-3.5 px-6">Nomor Agenda</th>
                        <th class="py-3.5 px-6">Perihal</th>
                        <th class="py-3.5 px-6">Instansi Pengirim</th>
                        <th class="py-3.5 px-6">Kategori</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    <?php $__empty_1 = true; $__currentLoopData = $suratMasukTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-4 px-6 font-bold text-blue-600 font-mono"><?php echo e($sm->nomor_agenda ?? '-'); ?></td>
                            <td class="py-4 px-6 font-semibold text-slate-800 max-w-xs truncate"><?php echo e($sm->perihal ?? '-'); ?></td>
                            <td class="py-4 px-6"><?php echo e($sm->instansi->nama_instansi ?? '-'); ?></td>
                            <td class="py-4 px-6"><?php echo e($sm->kategori->nama_kategori ?? '-'); ?></td>
                            <td class="py-4 px-6">
                                <?php
                                    $statusClasses = [
                                        'proses' => 'bg-amber-50 text-amber-600 border-amber-200',
                                        'disposisi' => 'bg-purple-50 text-purple-600 border-purple-200',
                                        'selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                    ];
                                    $currentStatus = strtolower($sm->status ?? 'baru');
                                    $badgeStyle = $statusClasses[$currentStatus] ?? 'bg-blue-50 text-blue-600 border-blue-200';
                                ?>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border <?php echo e($badgeStyle); ?>">
                                    <?php echo e(ucfirst($sm->status ?? 'Baru')); ?>

                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="<?php echo e(route('surat-masuk.show', $sm->id)); ?>" class="text-blue-600 hover:text-blue-800 font-semibold text-xs inline-flex items-center gap-1">
                                    Detail →
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 italic">
                                Belum ada data surat masuk terbaru.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- SCRIPT CHART.JS GRAFIK -->
<script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('suratChart').getContext('2d');

        // Gradient untuk Surat Masuk (Blue)
        const gradientMasuk = ctx.createLinearGradient(0, 0, 0, 300);
        gradientMasuk.addColorStop(0, 'rgba(37, 99, 235, 0.3)');
        gradientMasuk.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        // Gradient untuk Surat Keluar (Emerald)
        const gradientKeluar = ctx.createLinearGradient(0, 0, 0, 300);
        gradientKeluar.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
        gradientKeluar.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        // Data dari Controller Laravel
        const chartLabels = <?php echo json_encode($chartLabels, 15, 512) ?>;
        const dataMasuk   = <?php echo json_encode($chartDataMasuk, 15, 512) ?>;
        const dataKeluar  = <?php echo json_encode($chartDataKeluar, 15, 512) ?>;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: dataMasuk,
                        borderColor: '#2563eb',
                        backgroundColor: gradientMasuk,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Surat Keluar',
                        data: dataKeluar,
                        borderColor: '#10b981',
                        backgroundColor: gradientKeluar,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 10,
                        displayColors: true
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#94a3b8', font: { size: 11 } },
                        grid: { color: '#f1f5f9' }
                    }
                }
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Downloads\E-Arsip\resources\views/dashboard/index.blade.php ENDPATH**/ ?>