<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Laporan Surat Keluar</title>
<style>
    body{ font-family: sans-serif; font-size: 11px; }
    h2{ text-align:center; margin-bottom:4px; }
    p.sub{ text-align:center; color:#666; margin-top:0; }
    table{ width:100%; border-collapse: collapse; margin-top:15px; }
    th,td{ border:1px solid #999; padding:5px 8px; text-align:left; }
    th{ background:#eee; }
</style>
</head>
<body>
    <h2>LAPORAN SURAT KELUAR</h2>
    <p class="sub">Dicetak pada <?php echo e(now()->format('d-m-Y H:i')); ?></p>
    <table>
        <thead>
            <tr><th>No</th><th>No. Surat</th><th>Tgl Surat</th><th>Tujuan</th><th>Perihal</th><th>Kategori</th><th>Status</th></tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $suratKeluars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($i+1); ?></td>
                <td><?php echo e($s->nomor_surat); ?></td>
                <td><?php echo e($s->tanggal_surat->format('d-m-Y')); ?></td>
                <td><?php echo e($s->instansi->nama_instansi); ?></td>
                <td><?php echo e($s->perihal); ?></td>
                <td><?php echo e($s->kategori->nama_kategori); ?></td>
                <td><?php echo e(ucfirst($s->status)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH C:\Users\user\Downloads\E-Arsip\resources\views/exports/surat_keluar_pdf.blade.php ENDPATH**/ ?>