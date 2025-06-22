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
        <div class="section-title">Daftar Kunjungan</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Anggota</th>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>NIS/NIP</th>
                    <th>Tanggal Kunjungan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kunjungans as $index => $kunjungan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $kunjungan->anggota->kode_anggota }}</td>
                    <td>{{ $kunjungan->anggota->nama }}</td>
                    <td>{{ $kunjungan->anggota->tipe }}</td>
                    <td>
                        @if($kunjungan->anggota->siswa)
                            {{ $kunjungan->anggota->siswa->nis }}
                        @elseif($kunjungan->anggota->guru)
                            {{ $kunjungan->anggota->guru->nip }}
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d/m/Y') }}</td>
                    <td>{{ $kunjungan->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 