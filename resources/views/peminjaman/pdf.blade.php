<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h3 {
            margin: 0 0 10px 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Peminjaman Buku</h2>
        <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>

    <div class="section">
        <h3>Daftar Peminjaman</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Anggota</th>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>NIS/NIP</th>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjamans as $index => $peminjaman)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $peminjaman->anggota->kode_anggota }}</td>
                    <td>{{ $peminjaman->anggota->nama }}</td>
                    <td>{{ $peminjaman->anggota->tipe }}</td>
                    <td>
                        @if($peminjaman->anggota->siswa)
                            {{ $peminjaman->anggota->siswa->nis }}
                        @elseif($peminjaman->anggota->guru)
                            {{ $peminjaman->anggota->guru->nip }}
                        @endif
                    </td>
                    <td>{{ $peminjaman->buku->judul }}</td>
                    <td>{{ $peminjaman->buku->kategori->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)->format('d M Y') }}</td>
                    <td>{{ $peminjaman->pengembalian ? 'Dikembalikan' : 'Dipinjam' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>