@php 
    $siswas = $siswas->where('peminatan', $peminatan); 
    $kuota = $kuotaSnbps->where('peminatan', $peminatan)->pluck('kuota')->first();
@endphp
<table class="table-striped mb-0 table">
    <thead>
        <tr>
            <th>No.</th>
            <th>NISN</th>
            <th>Nama</th>
            <th>Kelas XII</th>
            <th>Rapor</th>
            <th>Prestasi</th>
            <th>Sikap</th>
            <th>Total Nilai</th>
            <th>Rank</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; $rank = 1; @endphp
        @forelse ($siswas as $siswa)
        <tr style="font-size: 14px;">
            <td>{{ $no++ }}</td>
            <td>{{ $siswa->nisn }}</td>
            <td>
                <a href="{{ route('siswa.edit', ['nisn' => $siswa->nisn]) }}" style="color:rgba(0, 0, 0, 0.664)">{{ $siswa->nama }}</a>
            </td>
            <td>{{ $siswa->kelas_12 }}</td>
            <td>{{ $siswa->spk_normalisasi->rapor ?? '-'}}</td>
            <td>{{ $siswa->spk_normalisasi->prestasi ?? '-' }}</td>
            <td>{{ $siswa->spk_normalisasi->sikap ?? '-' }}</td>
            <td>{{ $siswa->spk_normalisasi->rapor + $siswa->spk_normalisasi->prestasi + $siswa->spk_normalisasi->sikap }}</td>
            <td>
                @if ($rank <= $kuota)
                    @if ($siswa->snbp === 'Tidak Bersedia')
                        Tidak Bersedia
                    @else
                        # {{ $rank++ }}
                    
                    @endif
                @else
                    Non Legible
                @endif
            </td>
        </tr>

    @empty
        <tr>
            <td colspan="10">
                Data siswa {{ $peminatan }} belum Tersedia.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

