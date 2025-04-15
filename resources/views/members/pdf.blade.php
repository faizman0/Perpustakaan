<!DOCTYPE html>
<html>
<head>
    <title>Data Member</title>
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
    <h1>Data Member</h1>
    <p class="date">Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $index => $member)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $member->name }}</td>
                <td>{{ $member->nis }}</td>
                <td>{{ $member->class }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>