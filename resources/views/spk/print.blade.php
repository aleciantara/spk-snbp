<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Seleksi Eligible SNBP</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header img {
            width: 80px;
            height: auto;
        }
        .header h1 {
            margin: 10px 0 5px 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 5px 0 5px 0;
            font-size: 14px;
            
        }
        .header p {
            margin: 2px 0;
            font-size: 14px;
        }
        .header .address {
            font-size: 12px;
        }
        .content {
            font-size: 14px;
            line-height: 1.6;
        }
        table.static {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.static th, table.static td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        table.static th {
            background-color: #f2f2f2;
        }
        table.static tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
    <script>
        function displayDate() {
            // Get the current date
            const now = new Date();
            // Format the date as needed (e.g., "20 Mei 2024")
            const options = { day: '2-digit', month: 'long', year: 'numeric' };
            const formattedDate = now.toLocaleDateString('id-ID', options);

            // Insert the date into the HTML element with id "current-date"
            document.getElementById('current-date').textContent = formattedDate;
        }

        // Call the function when the page loads
        window.onload = displayDate;
    </script>
</head>

<body>

    <div class="header">
        <div style="float: left;">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRjcsu8b9yIvPwxOgNhpMNIw6Yp3F9X4KEOtQ&s" alt="Logo 1">
        </div>
        <div style="float: right;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Logo_Kota_Samarinda.png/467px-Logo_Kota_Samarinda.png" alt="Logo 2">
        </div>
        <div style="overflow: hidden;">
            <h1>PEMERINTAH PROVINSI KALIMANTAN TIMUR</h1>
            <h1>DINAS PENDIDIKAN DAN KEBUDAYAAN</h1>
            <h1>SMA NEGERI 1 SAMARINDA</h1>
            <p class="address">Jalan Drs. H. Anang Hasyim Samarinda, 75124, Telepon (0541) 741305 – 202409, Fax 200557,</p>
            <p class="address">Laman: sman1samarinda.sch.id Pos-el: info@sman1samarinda.sch.id</p>
        </div>
    </div>
    
    <div class="content">
        <div class=" mt-3">
            <h2 style="text-align: center; line-height:0px">PENGUMUMAN</h2>
            <p style="text-align: center; text-size: 16px">NOMOR: 400.3.8.1/1545/SMAN1</p>
        </div>
        
        <p>Kepada,</p>
        <p>Seluruh Siswa Kelas XII</p>

        <p>Sehubungan dengan telah dilaksanakannya proses seleksi siswa eligible melalui jalur Seleksi Nasional Berdasarkan Prestasi (SNBP) tahun 2024, bersama ini kami sampaikan bahwa hasil seleksi telah diumumkan. Kami mengucapkan selamat kepada para peserta yang telah dinyatakan <em>eligible</em> untuk melanjutkan proses pendaftaran SNBP sebagai siswa <em>eligible</em> SMAN 1 Samarinda.</p>

        <p>Adapun peserta yang dinyatakan <em>eligible</em> adalah sebagai berikut:</p>

        @foreach (['MIPA', 'IPS'] as $peminatan)
        @php $no = 1; @endphp
        <h3>Daftar Siswa Eligible SNBP - {{ $peminatan }}</h3>
        
        <table class="static">
            <thead>
                <tr ">
                    <th style="text-align: center">No.</th>
                    <th style="text-align: center">NISN</th>
                    <th style="text-align: center">Nama Lengkap</th>
                    <th style="text-align: center">Kelas</th>
                    <th style="text-align: center"s>Skor</th>
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

        <p>Bagi peserta yang telah dinyatakan <em>eligible</em>, harap segera melakukan langkah-langkah berikut:</p>

        <ol>
            <li>Melakukan registrasi ulang secara online melalui laman resmi SNPMB mulai tanggal 9 Januari - 15 Februari 2024.</li>
            <li>Mengunggah dokumen-dokumen yang diperlukan sesuai dengan ketentuan yang telah ditetapkan.</li>
            <li>Melaporkan proses pendaftaran SNBP pada guru BK.</li>
        </ol>

        <p>Demikian pengumuman ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>

        <p style="text-align: right;" >Samarinda, <span id="current-date"></span></p>
        <p style="text-align: right;" >Kepala,</p>
        <br ><br><br><br>
        <p style="text-align: right;" ><b>Rudi Suberata, S.Pd., M.Si.</b><br>
        <p style="text-align: right;" >NIP 19651231 198803 1 063</p>
        
    </div>
    
    

</body>

</html>
