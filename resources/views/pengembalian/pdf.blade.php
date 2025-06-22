<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pengembalian Perpustakaan</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px;
            font-size: 12px;
        }
        th { 
            background-color: #f2f2f2;
            text-align: center;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #e9ecef;
            padding: 10px;
            margin: 20px 0 10px 0;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 11px;
            color: white;
        }
        .badge-danger { background-color: #dc3545; }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; }
        .badge-primary { background-color: #007bff; }
        .badge-info { background-color: #17a2b8; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Pengembalian Perpustakaan</h2>
        @if($startDate && $endDate)
            <p>Periode: {{ $startDate }} - {{ $endDate }}</p>
        @endif
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <!-- Peminjaman Aktif -->
    <div class="section-title">Peminjaman Aktif</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Anggota</th>
                <th>Peminjam</th>
                <th>NIS/NIP</th>
                <th>Tipe</th>
                <th>Kelas</th>
                <th>Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamanBelumKembali as $p)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">
                    <span class="badge badge-primary">{{ $p->anggota->kode_anggota }}</span>
                </td>
                <td>{{ $p->anggota->nama ?? '-' }}</td>
                <td class="text-center">
                    @if($p->anggota->siswa)
                        {{ $p->anggota->siswa->nis ?? '-' }}
                    @elseif($p->anggota->guru)
                        {{ $p->anggota->guru->nip ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if($p->anggota->tipe == 'Siswa')
                        <span class="badge badge-success">Siswa</span>
                    @else
                        <span class="badge badge-info">Guru</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($p->anggota->siswa)
                        {{ $p->anggota->siswa->kelas->nama_kelas ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $p->buku->judul ?? '-' }}</td>
                <td class="text-center">{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                <td class="text-center">{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->addDays(7)->format('d/m/Y') : '-' }}</td>
                <td class="text-center">
                    @php
                        $jatuhTempo = $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->addDays(7) : null;
                        $now = \Carbon\Carbon::now();
                        $terlambat = $jatuhTempo ? $now->diffInDays($jatuhTempo, false) : 0;
                    @endphp
                    @if($terlambat < 0)
                        <span class="badge badge-danger">Terlambat {{ abs($terlambat) }} hari</span>
                    @else
                        <span class="badge badge-warning">Dipinjam</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data peminjaman aktif</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Riwayat Pengembalian -->
    <div class="section-title">Riwayat Pengembalian</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Anggota</th>
                <th>Peminjam</th>
                <th>NIS/NIP</th>
                <th>Tipe</th>
                <th>Kelas</th>
                <th>Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Status</th>
                <th>Tanggal Kembali</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengembalians as $p)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">
                    <span class="badge badge-primary">{{ $p->peminjaman->anggota->kode_anggota }}</span>
                </td>
                <td>{{ $p->peminjaman->anggota->nama ?? '-' }}</td>
                <td class="text-center">
                    @if($p->peminjaman->anggota->siswa)
                        {{ $p->peminjaman->anggota->siswa->nis ?? '-' }}
                    @elseif($p->peminjaman->anggota->guru)
                        {{ $p->peminjaman->anggota->guru->nip ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if($p->peminjaman->anggota->tipe == 'Siswa')
                        <span class="badge badge-success">Siswa</span>
                    @else
                        <span class="badge badge-info">Guru</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($p->peminjaman->anggota->siswa)
                        {{ $p->peminjaman->anggota->siswa->kelas->nama_kelas ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $p->peminjaman->buku->judul ?? '-' }}</td>
                <td class="text-center">{{ $p->peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($p->peminjaman->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                <td class="text-center">
                    @php
                        $tanggalKembali = \Carbon\Carbon::parse($p->tanggal_kembali);
                        $tanggalPinjam = \Carbon\Carbon::parse($p->peminjaman->tanggal_pinjam);
                        $jatuhTempo = $tanggalPinjam->addDays(7);
                        $terlambat = $tanggalKembali->diffInDays($jatuhTempo, false);
                    @endphp
                    <span class="badge badge-success">Dikembalikan</span>
                    @if($terlambat < 0)
                        <br><small style="color: #dc3545;">(Terlambat {{ abs($terlambat) }} hari)</small>
                    @endif
                </td>
                <td class="text-center">{{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data pengembalian</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html> 