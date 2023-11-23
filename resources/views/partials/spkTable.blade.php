@php $siswas = $siswas->where('peminatan', $peminatan); 
    $kuota = $kuotaSnbps->where('peminatan', $peminatan)->pluck('kuota')->first();
@endphp
<table class="table-striped mb-0 table">
    <thead>
        <tr>
            <th>No.</th>
            <th>NISN</th>
            <th>Nama</th>
            <th>Rapor</th>
            <th>Prestasi</th>
            <th>Sikap</th>
            <th>Skor</th>
            <th>Rank SNBP</th>
            @if (Auth::user()->role == 'admin')
            <th>Aksi</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @php $no = 1; $rank = 1; @endphp

        @forelse ($siswas as $siswa)
        <tr style="font-size: 14px;">
            <td>{{ $no++ }}</td>
            <td>{{ $siswa->nisn }}</td>
            <td>{{ $siswa->nama }}</td>
            <td>{{ $siswa->spk_preferensi->rapor ?? '-'}}</td>
            <td>{{ $siswa->spk_preferensi->prestasi ?? '-' }}</td>
            <td>{{ $siswa->spk_preferensi->sikap ?? '-' }}</td>
            <td>{{ $siswa->spk_preferensi->total*100 ?? '-' }}%</td>
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
            @if (Auth::user()->role == 'admin')
            <td>
                <a href="{{ route('siswa.edit', ['nisn' => $siswa->nisn]) }}" class="btn btn-md btn-warning"><i class="fas fa-edit"></i></a>
            </td>
            @endif
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
