<!DOCTYPE html>
<html>
<head>
    <title>Bukti Peminjaman Buku</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px 8px; }
        .section-title { font-weight: bold; margin-top: 20px; margin-bottom: 8px; }
        .bukti-box { border: 1px solid #333; padding: 16px; border-radius: 8px; }
        .ttd { margin-top: 40px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Bukti Peminjaman Buku</h2>
        <p>{{ config('app.name') }}</p>
    </div>
    <div class="bukti-box">
        <div class="section-title">Data Anggota</div>
        <table class="info-table">
            <tr><td>Kode Anggota</td><td>: {{ $peminjaman->anggota->kode_anggota }}</td></tr>
            <tr><td>Nama</td><td>: {{ $peminjaman->anggota->nama }}</td></tr>
            <tr><td>Tipe</td><td>: {{ $peminjaman->anggota->tipe }}</td></tr>
            <tr><td>NIS/NIP</td><td>:
                @if($peminjaman->anggota->siswa)
                    {{ $peminjaman->anggota->siswa->nis }}
                @elseif($peminjaman->anggota->guru)
                    {{ $peminjaman->anggota->guru->nip }}
                @endif
            </td></tr>
        </table>
        <div class="section-title">Data Buku</div>
        <table class="info-table">
            <tr><td>Judul Buku</td><td>: {{ $peminjaman->buku->judul }}</td></tr>
            <tr><td>Kategori</td><td>: {{ $peminjaman->buku->kategori->nama }}</td></tr>
            <tr><td>Pengarang</td><td>: {{ $peminjaman->buku->pengarang }}</td></tr>
            <tr><td>No. Klasifikasi</td><td>: {{ $peminjaman->buku->no_klasifikasi }}</td></tr>
        </table>
        <div class="section-title">Detail Peminjaman</div>
        <table class="info-table">
            <tr><td>Tanggal Pinjam</td><td>: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td></tr>
            <tr><td>Tanggal Kembali</td><td>: {{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : '-' }}</td></tr>
            <tr><td>Status</td><td>: {!! $peminjaman->status ?? ($peminjaman->pengembalian ? 'Dikembalikan' : 'Dipinjam') !!}</td></tr>
        </table>
        <div class="ttd">
            <p>{{ config('app.name') }}, {{ now()->format('d-m-Y') }}</p>
            <br><br>
            <p>Petugas</p>
            <br><br>
            <p>_________________________</p>
        </div>
    </div>
</body>
</html> 