@php $siswas = $siswas->where('peminatan', $peminatan); 
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
        {{-- @php $no = ($currentPage - 1) * $siswas->perPage() + 1 @endphp --}}
        @php $no = 1; $rank = 1; @endphp
        @forelse ($siswas as $siswa)
        <tr style="font-size: 14px;">
            <td>{{ $no++ }}</td>
            <td>{{ $siswa->nisn }}</td>
            <td>
                <a href="{{ route('siswa.edit', ['nisn' => $siswa->nisn]) }}" style="color:rgba(0, 0, 0, 0.664)">{{ $siswa->nama }}</a>
            </td>
            <td>{{ $siswa->kelas_12 }}</td>
            <td>{{ $siswa->spk_kriteria->rapor ?? '-'}}</td>
            <td>{{ $siswa->spk_kriteria->prestasi ?? '-' }}</td>
            <td>{{ $siswa->spk_kriteria->sikap ?? '-' }}</td>
            <td>{{ $siswa->spk_kriteria->rapor + $siswa->spk_kriteria->prestasi + $siswa->spk_kriteria->sikap }}</td>
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
{{-- <div class="card-footer text-center" bis_skin_checked="1">
    <nav class="d-inline-block">
    <ul class="pagination mt-3 mb-1">
        {{ $siswas->links('pagination::bootstrap-4') }}
    </ul>
    </nav>
</div> --}}
