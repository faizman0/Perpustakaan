<!DOCTYPE html>
<html>
<head>
    <title>Data Pengembalian</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Data Pengembalian Perpustakaan</h2>
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengembalian as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->peminjaman->anggota->nama }}</td>
                <td>{{ $item->peminjaman->buku->judul }}</td>
                <td>{{ \Carbon\Carbon::parse($item->peminjaman->tanggal_pinjam)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->return_date)->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 