<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Buku Perpustakaan</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .info {
            margin-bottom: 20px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>DATA BUKU PERPUSTAKAAN</h2>
        <p>SD Negeri Banguntapan</p>
    </div>

    <div class="info">
        <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
        <p>Total Buku: {{ count($bukus) }} judul</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="20%">Judul</th>
                <th width="8%">No. Inv</th>
                <th width="8%">No. Klasifikasi</th>
                <th width="10%">Pengarang</th>
                <th width="10%">Penerbit</th>
                <th width="5%">Tahun</th>
                <th width="5%">Ed Ke-</th>
                <th width="8%">ISBN</th>
                <th width="5%">Kolase</th>
                <th width="3%">Qty</th>
                <th width="8%">Keterangan</th>
                <th width="7%">Kategori</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bukus as $index => $buku)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $buku->judul }}</td>
                <td>{{ $buku->no_inventaris }}</td>
                <td>{{ $buku->no_klasifikasi }}</td>
                <td>{{ $buku->pengarang }}</td>
                <td>{{ $buku->penerbit }}</td>
                <td>{{ $buku->tahun_terbit }}</td>
                <td>{{ $buku->edisi ?? '-' }}</td>
                <td>{{ $buku->isbn ?? '-' }}</td>
                <td>{{ $buku->kolase ?? '-' }}</td>
                <td>{{ $buku->jumlah }}</td>
                <td>{{ $buku->keterangan ?? '-' }}</td>
                <td>{{ $buku->kategori->nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <p>Kepala Perpustakaan,</p>
        <div class="signature-line"></div>
        <p>NIP. 123456789</p>
    </div>
</body>
</html>