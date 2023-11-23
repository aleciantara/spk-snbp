<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="{{csrf_token()}}">
    <title>Print Daftar Siswa Eligible SNBP</title>
    <style>
        table.static {
            border-collapse: collapse;
            width: 95%;
            margin: 20px auto;
        }

        th, td {
            border: 1px solid #545454;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        p {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    
    <div class="form-group">
        @foreach (['MIPA', 'IPS'] as $peminatan)
        @php $no = 1; @endphp
        <p>Daftar Siswa Eligible SNBP - {{ $peminatan }}</p>
        
        <table class="static">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NISN</th>
                    <th>Nama Lengkap</th>
                    <th>Kelas</th>
                    <th>Skor</th>
                </tr>
            </thead>
            <tbody>
                
                @php
                    $siswasFiltered = $siswas->where('peminatan', $peminatan);
                    $kuota = $kuotaSnbps->where('peminatan', $peminatan)->pluck('kuota')->first();
                @endphp

                @forelse ($siswasFiltered as $siswa)
                    @if($no <= $kuota)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $siswa->nisn }}</td>
                            <td>{{ $siswa->nama }}</td>
                            <td>{{ $siswa->kelas_12 ?? '-' }}</td>
                            <td>{{ $siswa->spk_preferensi->total * 100 ?? '-' }}%</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="5">Data siswa {{ $peminatan }} belum tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @endforeach
    </div>

</body>

</html>
