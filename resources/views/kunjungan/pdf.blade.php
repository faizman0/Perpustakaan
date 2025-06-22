<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kunjungan Perpustakaan</title>
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
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Kunjungan Perpustakaan</h2>
        @if($startDate && $endDate)
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        @endif
        <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Kunjungan Siswa</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kunjunganSiswa as $index => $kunjungan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d/m/Y') }}</td>
                    <td>{{ $kunjungan->siswa->nama }}</td>
                    <td>{{ $kunjungan->siswa->kelas->nama_kelas }}</td>
                    <td>{{ $kunjungan->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Kunjungan Guru</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Guru</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kunjunganGuru as $index => $kunjungan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $kunjungan->tanggal_kunjungan }}</td>
                    <td>{{ $kunjungan->guru->nama }}</td>
                    <td>{{ $kunjungan->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 