<?php

namespace App\Http\Controllers;

use App\Models\BobotKriteria;
use App\Models\KuotaSnbp;
use App\Models\Prestasi;
use App\Models\Rapor;
use App\Models\Siswa;
use App\Models\SpkKriteria;
use App\Models\SpkNormalisasi;
use App\Models\SpkPreferensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class SPKController extends Controller
{
    public function index()
    {
        $bobotKriterias = BobotKriteria::all();
        $kuotaSnbps = KuotaSnbp::all();
        $siswas = Siswa::all();

        //extract spk_kriterias value for each siswas
        foreach ($siswas as $siswa){
            $this->storeSpkKriteria($siswa->nisn);
        }

        //normalisasi data kriteria siswa
        $spkKriterias= SpkKriteria::all();

        foreach ($spkKriterias as $spkKriteria){
            $this->normalizeSpkKriteria($spkKriteria);
        }

        //hitung nilai preferensi siswa sesuai bobot kriteria
        $spkNormalisasis= SpkNormalisasi::all();

        foreach ($spkNormalisasis as $spkNormalisasi){
            $this->hitungSkorPreferensi($spkNormalisasi);
        }

        $siswas = Siswa::join('spk_preferensis', 'siswas.nisn', '=', 'spk_preferensis.nisn')
            // ->where('siswas.snbp','=', 'Bersedia')
            // ->when($search, function ($query) use ($search) {
            //     $query->where('siswas.nama', 'like', '%' . $search . '%')
            //         ->orWhere('siswas.nisn', 'like', '%' . $search . '%');
            // })
            ->orderBy('spk_preferensis.total', 'desc')
            ->select('siswas.*')
            ->get();

        return view('spk.index', compact('bobotKriterias', 'siswas', 'kuotaSnbps'));
    }

    public function print()
    {
        $kuotaSnbps = KuotaSnbp::all();

        $siswas = Siswa::join('spk_preferensis', 'siswas.nisn', '=', 'spk_preferensis.nisn')
            ->where('siswas.snbp','=', 'Bersedia')
            ->orderBy('spk_preferensis.total', 'desc')
            ->select('siswas.*')
            ->get();
        
        // Return the printable view
        return  view('spk.print', compact('siswas', 'kuotaSnbps'));
    }

    public function kriteria()
    {
        $bobotKriterias = BobotKriteria::all();
        $kuotaSnbps = KuotaSnbp::all();
        
        $siswas = Siswa::join('spk_kriterias', 'siswas.nisn', '=', 'spk_kriterias.nisn')
            ->select('siswas.*', DB::raw('SUM(spk_kriterias.rapor + spk_kriterias.prestasi + spk_kriterias.sikap) AS total'))
            ->groupBy('siswas.nisn', 'siswas.nama', 'siswas.kelas_10', 'siswas.kelas_11', 'siswas.kelas_12','siswas.sikap', 'siswas.peminatan','siswas.agama',  'siswas.snbp','siswas.angkatan', 'siswas.updated_at', 'siswas.created_at') // Group by nisn to avoid duplicate rows
            ->orderBy('total', 'desc')
            ->get();

        return view('spk.kriteria', compact('bobotKriterias', 'siswas', 'kuotaSnbps'));
    }

    public function normalisasi()
    {
        $bobotKriterias = BobotKriteria::all();
        $kuotaSnbps = KuotaSnbp::all();

        $siswas = Siswa::join('spk_normalisasis', 'siswas.nisn', '=', 'spk_normalisasis.nisn')
            ->select('siswas.*', DB::raw('SUM(spk_normalisasis.rapor + spk_normalisasis.prestasi + spk_normalisasis.sikap) AS total'))
            ->groupBy('siswas.nisn', 'siswas.nama', 'siswas.kelas_10', 'siswas.kelas_11', 'siswas.kelas_12','siswas.sikap', 'siswas.peminatan','siswas.agama',  'siswas.snbp','siswas.angkatan', 'siswas.updated_at', 'siswas.created_at') // Group by nisn to avoid duplicate rows
            ->orderBy('total', 'desc')
            ->get();

        return view('spk.normalisasi', compact('bobotKriterias', 'siswas', 'kuotaSnbps'));
    }

    public function setBobot(Request $request)
    {
        $totalBobot = $request->input('RaporBobot') + $request->input('PrestasiBobot') + $request->input('SikapBobot') ;

        
        if ($totalBobot == 1.0) {
            // Save or update the bobot records in the database
            $this->updateBobot('Rapor', $request->input('RaporBobot'));
            $this->updateBobot('Prestasi', $request->input('PrestasiBobot'));
            $this->updateBobot('Sikap', $request->input('SikapBobot'));

            // Redirect back or wherever needed
            return redirect()->back()->with('success', 'Bobot Kriteria Berhasil Diubah');
        }
        else{
            return redirect()->back()->with('error','Total Bobot Kriteria Harus Berjumlah 1');

        }
        
    }

    private function updateBobot($namaKriteria, $bobot)
    {
        // Find the existing bobot record or create a new one
        $bobotKriteria = BobotKriteria::where('nama_kriteria', $namaKriteria)->first();
        
        // Update the bobot value
        $bobotKriteria->bobot = $bobot;

        // Save the record
        $bobotKriteria->save();
    }

    public function setKuota(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'MIPAKuota' => 'required|integer',
            'IPSKuota' => 'required|integer',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Kuota SNBP harus integer');
        }
    

        // Save or update the bobot records in the database
        $this->updateKuota('MIPA', $request->input('MIPAKuota'));
        $this->updateKuota('IPS', $request->input('IPSKuota'));

        // Redirect back or wherever needed
        return redirect()->back()->with('success', 'Kuota SNBP Berhasil Diubah');
        
    }

    private function updateKuota($peminatan, $kuota)
    {
        // Find the existing bobot record or create a new one
        $kuotaSnbp = KuotaSnbp::where('peminatan', $peminatan)->first();
        
        // Update the bobot value
        $kuotaSnbp->kuota = $kuota;

        // Save the record
        $kuotaSnbp->save();
    }

    public function storeSpkKriteria($nisn)
    {
        //get average rapor
        $rataRapor = $this->getRataRapor($nisn);

        $poinPrestasi = $this->getpoinPrestasi($nisn);

        $sikap = $this->getSpkSikap($nisn);

        SpkKriteria::updateOrCreate(
            ['nisn' => $nisn],
            ['rapor' => $rataRapor, 
            'prestasi' => $poinPrestasi, 
            'sikap' => $sikap]
        );
    }

    private function getRataRapor($nisn)
    {
        $allRataNilaiP = Rapor::where('nisn', $nisn)->pluck('rata_nilai_p')->toArray();
        
        $rataRapor = (array_sum($allRataNilaiP)/count($allRataNilaiP));
        $rataRapor = number_format($rataRapor, 2);

        return $rataRapor;
    }

    private function getPoinPrestasi($nisn)
    {
        // Retrieve prestasi data for the given nisn
        $prestasiData = Prestasi::whereHas('prestasiSiswa', function ($query) use ($nisn) {
                                            $query->where('nisn', $nisn);
                                        })->get();

        if(!empty($prestasiData)){
            // Initialize the sum of prestasi points
            $totalPoinPrestasi = 0.0; // Set initial value as a float

            // Loop through each prestasi record and add its poin value to the total
            foreach ($prestasiData as $prestasi) {
                // Ensure that the 'poin' attribute exists and is a numeric value
                if (isset($prestasi->poin) && is_numeric($prestasi->poin)) {
                    $totalPoinPrestasi += (float)$prestasi->poin; // Cast to float
                }
            }
        }else{
            $totalPoinPrestasi = 0;
        }

        return $totalPoinPrestasi;
    }

    private function getSpkSikap($nisn)
    {
        $sikap = Siswa::where('nisn', $nisn)->value('sikap');

        if  ($sikap === "Sangat Baik"){
            return 5;
        } elseif ($sikap === "Baik"){
            return 4;
        } elseif ($sikap === "Cukup"){
            return 3;
        } elseif ($sikap === "Buruk"){
            return 2;
        } elseif ($sikap === "Sangat Buruk"){
            return 1;
        } else {
            return 4;
        }
        
    }

    public function normalizeSpkKriteria($spkKriteria)
    {
        $maxRapor    = 100;
        $maxPrestasi = $this->getMaxPrestasi();
        $maxSikap    = 5;

        $normalizedRapor    = $spkKriteria->rapor / $maxRapor;
        $normalizedPrestasi = $spkKriteria->prestasi / $maxPrestasi;
        $normalizedSikap    = $spkKriteria->sikap / $maxSikap;

        SpkNormalisasi::updateOrCreate(
            ['nisn'    => $spkKriteria->nisn],
            ['rapor'   => $normalizedRapor, 
            'prestasi' => $normalizedPrestasi, 
            'sikap'    => $normalizedSikap]
        );
    }

    private function getMaxRapor()
    {
        return SpkKriteria::max('rapor');
    }

    private function getMaxPrestasi()
    {
        return SpkKriteria::max('prestasi');
    }

    public function hitungSkorPreferensi($spkNormalisasi)
    {
        $bobotRapor    = $this->getBobotRapor();
        $bobotPrestasi = $this->getBobotPrestasi();
        $bobotSikap    = $this->getBobotSikap();

        $preferensiRapor    = $spkNormalisasi->rapor * $bobotRapor;
        $preferensiPrestasi = $spkNormalisasi->prestasi * $bobotPrestasi;
        $preferensiSikap    = $spkNormalisasi->sikap * $bobotSikap;
        $totalPreferensi    = $preferensiRapor + $preferensiPrestasi + $preferensiSikap;

        // dd($preferensiRapor);

        SpkPreferensi::updateOrCreate(
            ['nisn'    => $spkNormalisasi->nisn],
            ['rapor'   => $preferensiRapor, 
            'prestasi' => $preferensiPrestasi, 
            'sikap'    => $preferensiSikap,
            'total'    => $totalPreferensi]
        );
    }

    private function getBobotRapor()
    {
        return BobotKriteria::where('nama_kriteria', 'Rapor')->value('bobot');
    }

    private function getBobotPrestasi()
    {
        return BobotKriteria::where('nama_kriteria', 'Prestasi')->value('bobot');
    }

    private function getBobotSikap()
    {
        return BobotKriteria::where('nama_kriteria', 'Sikap')->value('bobot');
    }
}
