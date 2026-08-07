<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Label Arsip</title>
    <style>
        body{ font-family: monospace; padding:20px; }
        .label{ border:2px solid #000; width:340px; padding:12px; }
        .label h3{ margin:0 0 6px; font-size:14px; border-bottom:1px solid #000; padding-bottom:4px; }
        .label p{ margin:2px 0; font-size:12px; }
    </style>
</head>
<body onload="window.print()">
    <div class="label">
        <h3>ARSIP SURAT MASUK</h3>
        <p><strong>No. Agenda:</strong> {{ $suratMasuk->nomor_agenda }}</p>
        <p><strong>No. Surat:</strong> {{ $suratMasuk->nomor_surat }}</p>
        <p><strong>Tgl Terima:</strong> {{ $suratMasuk->tanggal_terima->format('d-m-Y') }}</p>
        <p><strong>Dari:</strong> {{ $suratMasuk->instansi->nama_instansi }}</p>
        <p><strong>Perihal:</strong> {{ $suratMasuk->perihal }}</p>
        <p><strong>Kategori:</strong> {{ $suratMasuk->kategori->nama_kategori }}</p>
        <p><strong>Lokasi:</strong> {{ $suratMasuk->lokasi_arsip_fisik ?? '-' }}</p>
    </div>
</body>
</html>
