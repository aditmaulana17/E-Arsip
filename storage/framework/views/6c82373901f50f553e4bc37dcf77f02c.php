<?php $__env->startSection('title', 'Detail Surat Keluar'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- HEADER PAGE & TOMBOL AKSI -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('surat-keluar.index')); ?>" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 shadow-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Detail Surat Keluar</h1>
                <p class="text-sm text-slate-500 mt-0.5">Informasi lengkap penerbitan dan arsip berkas surat keluar.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Edit Surat Keluar -->
            <a href="<?php echo e(route('surat-keluar.edit', $suratKeluar)); ?>" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-xl transition border border-amber-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit Surat</span>
            </a>
        </div>
    </div>

    <!-- MAIN CARD: INFORMASI UTAMA SURAT KELUAR -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-6">
        
        <!-- Perihal, Nomor Surat & Badge Status -->
        <div class="flex justify-between items-start pb-4 border-b border-slate-100 gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800 leading-snug"><?php echo e($suratKeluar->perihal); ?></h3>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                    <span>No. Surat: <strong class="text-blue-600 font-semibold font-mono bg-blue-50 px-2 py-0.5 rounded border border-blue-100"><?php echo e($suratKeluar->nomor_surat); ?></strong></span>
                </p>
            </div>

            <?php
                $statusKey = strtolower($suratKeluar->status ?? 'draf');
                $badgeClass = match($statusKey) {
                    'draf'      => 'bg-slate-100 text-slate-700 border-slate-200',
                    'diproses'  => 'bg-amber-50 text-amber-700 border-amber-200',
                    'disetujui' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'dikirim'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'diarsipkan' => 'bg-purple-50 text-purple-700 border-purple-200',
                    default     => 'bg-slate-100 text-slate-600 border-slate-200'
                };
            ?>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border shrink-0 <?php echo e($badgeClass); ?>">
                <?php echo e(ucfirst($statusKey)); ?>

            </span>
        </div>

        <!-- Detail Grid Metadata -->
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs">
            <div>
                <dt class="font-bold uppercase tracking-wider text-slate-400">Instansi Tujuan</dt>
                <dd class="mt-1.5 text-sm font-semibold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-100">
                    <?php echo e($suratKeluar->instansi->nama_instansi ?? $suratKeluar->tujuan ?? '-'); ?>

                </dd>
            </div>

            <div>
                <dt class="font-bold uppercase tracking-wider text-slate-400">Kategori Surat</dt>
                <dd class="mt-1.5 text-sm font-semibold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-100">
                    <?php echo e($suratKeluar->kategori->nama_kategori ?? $suratKeluar->kategoriSurat->nama_kategori ?? '-'); ?>

                </dd>
            </div>

            <div>
                <dt class="font-bold uppercase tracking-wider text-slate-400">Tanggal Surat</dt>
                <dd class="mt-1.5 text-sm font-semibold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-100">
                    <?php echo e(optional($suratKeluar->tanggal_surat)->format('d-m-Y') ?? (is_string($suratKeluar->tanggal_surat) ? $suratKeluar->tanggal_surat : '-')); ?>

                </dd>
            </div>

            <div>
                <dt class="font-bold uppercase tracking-wider text-slate-400">Dibuat Oleh</dt>
                <dd class="mt-1.5 text-sm font-semibold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-100">
                    <?php echo e($suratKeluar->pembuat->name ?? $suratKeluar->user->name ?? '-'); ?>

                </dd>
            </div>

            <div class="md:col-span-2">
                <dt class="font-bold uppercase tracking-wider text-slate-400">Ditandatangani Oleh</dt>
                <dd class="mt-1.5 text-sm font-semibold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-100">
                    <?php echo e($suratKeluar->penandatangan->name ?? '-'); ?>

                </dd>
            </div>
        </dl>

        <!-- Ringkasan / Isi Surat -->
        <?php if(!empty($suratKeluar->ringkasan)): ?>
            <div class="pt-4 border-t border-slate-100">
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Ringkasan / Isi Surat</dt>
                <dd class="text-sm text-slate-600 bg-slate-50 p-4 rounded-xl border border-slate-100 leading-relaxed whitespace-pre-line">
                    <?php echo e($suratKeluar->ringkasan); ?>

                </dd>
            </div>
        <?php endif; ?>

        <!-- Lampiran Berkas Digital -->
        <div class="pt-4 border-t border-slate-100">
            <dt class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Berkas Lampiran (Digital)</dt>
            <?php if(!empty($suratKeluar->lampiran_file)): ?>
                <a href="<?php echo e(route('lampiran.preview', $suratKeluar->lampiran_file)); ?>" target="_blank" class="inline-flex items-center gap-2 text-xs font-semibold bg-blue-50 text-blue-600 px-4 py-2.5 rounded-xl border border-blue-200/80 hover:bg-blue-100 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>Lihat Berkas Lampiran (PDF)</span>
                </a>
            <?php else: ?>
                <p class="text-xs text-slate-400 italic bg-slate-50 p-3 rounded-xl border border-slate-100">Tidak ada lampiran berkas digital.</p>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Downloads\E-Arsip\resources\views/surat_keluar/show.blade.php ENDPATH**/ ?>