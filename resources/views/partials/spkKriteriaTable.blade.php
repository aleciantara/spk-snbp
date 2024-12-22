@php 
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
        @php
        $no = ($siswas->currentPage() - 1) * $siswas->perPage() + 1;
        // Calculate the starting rank based on the current page and the initial rank
        @endphp

        @forelse ($siswas as $siswa)
        <tr style="font-size: 14px;">
            <td>{{ $no++ }}</td>
            <td>{{ $siswa->nisn }}</td>
            <td>{{ $siswa->nama }}</td>
            <td>{{ $siswa->rapor ?? '-'}}</td>
            <td>{{ $siswa->prestasi ?? '-' }}</td>
            <td>{{ $siswa->sikap ?? '-' }}</td>
            <td>{{ $siswa->total ?? '-' }}</td>
            <td>
                @if ($siswa->snbp === 'Bersedia' && $rank <= $kuota)
                    #{{ $rank }}
                    @php $rank++; @endphp {{-- Increment the rank only if the condition is met --}}
                @elseif ($siswa->snbp === 'Tidak Bersedia')
                    Tidak Bersedia
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
<div class="card-footer text-center">
    <nav class="d-inline-block">
        <ul class="pagination mt-3 mb-1">
            {{ $siswas->links('pagination::bootstrap-4') }}
        </ul>
    </nav>
</div>

