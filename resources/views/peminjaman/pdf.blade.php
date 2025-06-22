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
        <h3>Peminjaman Siswa</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjamanSiswa as $index => $peminjaman)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $peminjaman->siswa->nis }}</td>
                    <td>{{ $peminjaman->siswa->nama }}</td>
                    <td>{{ $peminjaman->siswa->kelas->nama_kelas }}</td>
                    <td>{{ $peminjaman->buku->judul }}</td>
                    <td>{{ $peminjaman->buku->kategori->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                    <td>{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : '-' }}</td>
                    <td>{{ $peminjaman->tanggal_kembali ? 'Dikembalikan' : 'Dipinjam' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Peminjaman Guru</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjamanGuru as $index => $peminjaman)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $peminjaman->guru->nip }}</td>
                    <td>{{ $peminjaman->guru->nama }}</td>
                    <td>{{ $peminjaman->buku->judul }}</td>
                    <td>{{ $peminjaman->buku->kategori->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                    <td>{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : '-' }}</td>
                    <td>{{ $peminjaman->tanggal_kembali ? 'Dikembalikan' : 'Dipinjam' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>