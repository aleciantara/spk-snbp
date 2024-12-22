<!DOCTYPE html>
<html>
<head>
    <title>SPK Detail</title>
</head>
<body>
    <h1>MIPA Students</h1>
    <table border="1">
        <thead>
            <tr>
                <th>NISN</th>
                <th>Nama</th>
                <th>Kriteria C1</th>
                <th>Kriteria C2</th>
                <th>Kriteria C3</th>
                <th>Normalisasi C1</th>
                <th>Normalisasi C2</th>
                <th>Normalisasi C3</th>
                <th>Preferensi C1</th>
                <th>Preferensi C2</th>
                <th>Preferensi C3</th>
                <th>Preferensi Skor</th>
                <th>Rank</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswasMIPA as $siswa)
                <tr>
                    <td>{{ $siswa->nisn }}</td>
                    <td>{{ $siswa->nama }}</td>
                    <td>{{ $siswa->kriteria_c1 }}</td>
                    <td>{{ $siswa->kriteria_c2 }}</td>
                    <td>{{ $siswa->kriteria_c3 }}</td>
                    <td>{{ $siswa->normalisasi_c1 }}</td>
                    <td>{{ $siswa->normalisasi_c2 }}</td>
                    <td>{{ $siswa->normalisasi_c3 }}</td>
                    <td>{{ $siswa->preferensi_c1 }}</td>
                    <td>{{ $siswa->preferensi_c2 }}</td>
                    <td>{{ $siswa->preferensi_c3 }}</td>
                    <td>{{ $siswa->preferensi_c1 + $siswa->preferensi_c2 + $siswa->preferensi_c3 }}</td>
                    <td>{{ $siswa->rank }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h1>IPS Students</h1>
    <table border="1">
        <thead>
            <tr>
                <th>NISN</th>
                <th>Nama</th>
                <th>Kriteria C1</th>
                <th>Kriteria C2</th>
                <th>Kriteria C3</th>
                <th>Normalisasi C1</th>
                <th>Normalisasi C2</th>
                <th>Normalisasi C3</th>
                <th>Preferensi C1</th>
                <th>Preferensi C2</th>
                <th>Preferensi C3</th>
                <th>Preferensi Skor</th>
                <th>Rank</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswasIPS as $siswa)
                <tr>
                    <td>{{ $siswa->nisn }}</td>
                    <td>{{ $siswa->nama }}</td>
                    <td>{{ $siswa->kriteria_c1 }}</td>
                    <td>{{ $siswa->kriteria_c2 }}</td>
                    <td>{{ $siswa->kriteria_c3 }}</td>
                    <td>{{ $siswa->normalisasi_c1 }}</td>
                    <td>{{ $siswa->normalisasi_c2 }}</td>
                    <td>{{ $siswa->normalisasi_c3 }}</td>
                    <td>{{ $siswa->preferensi_c1 }}</td>
                    <td>{{ $siswa->preferensi_c2 }}</td>
                    <td>{{ $siswa->preferensi_c3 }}</td>
                    <td>{{ $siswa->preferensi_c1 + $siswa->preferensi_c2 + $siswa->preferensi_c3 }}</td>
                    <td>{{ $siswa->rank }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
