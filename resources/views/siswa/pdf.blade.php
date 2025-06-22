<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
        .date { text-align: right; }
    </style>
</head>
<body>
    <h1>Data Siswa</h1>
    <p class="date">Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Jenis Kelamin</th>
                <th>Kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $index => $siswa)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $siswa->nama }}</td>
                <td>{{ $siswa->nis }}</td>
                <td>{{ $siswa->jenis_kelamin }}</td>
                <td>{{ $siswa->kelas }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>