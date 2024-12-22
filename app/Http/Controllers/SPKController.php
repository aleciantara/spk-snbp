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
    public function index(Request $request)
    {
        $bobotKriterias = BobotKriteria::all();
        $kuotaSnbps = KuotaSnbp::all();
        $siswas = Siswa::all();
        $page = $request->input('page', 1);
        $perPage = 40;

        $isInitialRequest = $request->input('initial', false);

        if ($isInitialRequest) {
            //extract spk_kriterias value for each siswas
            foreach ($siswas as $siswa){
                $this->storeSpkKriteria($siswa->nisn);
            }

            //Retrieve all max value for each criteria
            $maxRapor    = $this->getMaxRapor();
            $maxPrestasi = $this->getMaxPrestasi();
            $maxSikap   = $this->getMaxSikap();

            // dd($maxPrestasi,$maxRapor,$maxSikap);

            //normalisasi data kriteria siswa
            $spkKriterias= SpkKriteria::all();
            foreach ($spkKriterias as $spkKriteria){
                $this->normalizeSpkKriteria($spkKriteria, $maxRapor, $maxPrestasi, $maxSikap);
            }

            //hitung nilai preferensi siswa sesuai bobot kriteria
            $spkNormalisasis= SpkNormalisasi::all();
            foreach ($spkNormalisasis as $spkNormalisasi){
                $this->hitungSkorPreferensi($spkNormalisasi);
            }
        }
        
        $queryMIPA = Siswa::join('spk_preferensis', 'siswas.nisn', '=', 'spk_preferensis.nisn')
                    ->where('peminatan', 'MIPA')
                    ->orderBy('spk_preferensis.total', 'desc');

        $queryIPS = Siswa::join('spk_preferensis', 'siswas.nisn', '=', 'spk_preferensis.nisn')
                    ->where('peminatan', 'IPS')
                    ->orderBy('spk_preferensis.total', 'desc');

        // Initialize the variable
        $startRankMIPA = 1;
        $startRankIPS = 1;

        // Only calculate if the current page is greater than 1
        if ($page > 1) {
            $startRankMIPA = $queryMIPA->get() // Fetch all entries that match
                                        ->take(($page - 1) * $perPage)
                                        ->where('snbp', 'Bersedia')
                                        ->count() // Take records up to the end of the last page
                                        +1;
                                            
            }
        if ($page > 1) {
            $startRankIPS = $queryIPS->get() // Fetch all entries that match
                                    ->take(($page - 1) * $perPage)
                                    ->where('snbp', 'Bersedia')
                                    ->count() // Take records up to the end of the last page
                                    +1;
            }

        // Proceed with other operations, for example, pagination
        $siswasMIPA = $queryMIPA->paginate($perPage, ['*'], 'page', $page);
        $siswasIPS = $queryIPS->paginate($perPage, ['*'], 'page', $page);

    
        return view('spk.index', compact(
            'bobotKriterias','siswasMIPA', 'siswasIPS', 'kuotaSnbps',
            'startRankMIPA',
            'startRankIPS', 
        ));
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

    public function storeSpkKriteria($nisn)
    {
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

    public function normalizeSpkKriteria($spkKriteria, $maxRapor, $maxPrestasi, $maxSikap)
    {
        $normalizedRapor    = $spkKriteria->rapor / $maxRapor;
        $normalizedPrestasi = $maxPrestasi != 0 ? $spkKriteria->prestasi / $maxPrestasi : 0;
        $normalizedSikap    = $spkKriteria->sikap / $maxSikap;

        SpkNormalisasi::updateOrCreate(
            ['nisn'    => $spkKriteria->nisn],
            ['rapor'   => $normalizedRapor, 
            'prestasi' => $normalizedPrestasi, 
            'sikap'    => $normalizedSikap]
        );
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

        SpkPreferensi::updateOrCreate(
            ['nisn'    => $spkNormalisasi->nisn],
            ['rapor'   => $preferensiRapor, 
            'prestasi' => $preferensiPrestasi, 
            'sikap'    => $preferensiSikap,
            'total'    => $totalPreferensi]
        );
    }

    public function detailSPK()
    {
        // Fetching MIPA students data
        $siswasMIPA = DB::table('siswas')
            ->join('spk_kriterias', 'siswas.nisn', '=', 'spk_kriterias.nisn')
            ->join('spk_normalisasis', 'siswas.nisn', '=', 'spk_normalisasis.nisn')
            ->join('spk_preferensis', 'siswas.nisn', '=', 'spk_preferensis.nisn')
            ->where('siswas.peminatan', 'MIPA')
            ->select(
                'siswas.nisn',
                'siswas.nama',
                'spk_kriterias.rapor as kriteria_c1',
                'spk_kriterias.prestasi as kriteria_c2',
                'spk_kriterias.sikap as kriteria_c3',
                'spk_normalisasis.rapor as normalisasi_c1',
                'spk_normalisasis.prestasi as normalisasi_c2',
                'spk_normalisasis.sikap as normalisasi_c3',
                'spk_preferensis.rapor as preferensi_c1',
                'spk_preferensis.prestasi as preferensi_c2',
                'spk_preferensis.sikap as preferensi_c3',
                'spk_preferensis.total'
            )
            ->orderBy('siswas.nisn') // Order by NISN
            ->get();

        // Fetching IPS students data
        $siswasIPS = DB::table('siswas')
            ->join('spk_kriterias', 'siswas.nisn', '=', 'spk_kriterias.nisn')
            ->join('spk_normalisasis', 'siswas.nisn', '=', 'spk_normalisasis.nisn')
            ->join('spk_preferensis', 'siswas.nisn', '=', 'spk_preferensis.nisn')
            ->where('siswas.peminatan', 'IPS')
            ->select(
                'siswas.nisn',
                'siswas.nama',
                'spk_kriterias.rapor as kriteria_c1',
                'spk_kriterias.prestasi as kriteria_c2',
                'spk_kriterias.sikap as kriteria_c3',
                'spk_normalisasis.rapor as normalisasi_c1',
                'spk_normalisasis.prestasi as normalisasi_c2',
                'spk_normalisasis.sikap as normalisasi_c3',
                'spk_preferensis.rapor as preferensi_c1',
                'spk_preferensis.prestasi as preferensi_c2',
                'spk_preferensis.sikap as preferensi_c3',
                'spk_preferensis.total'
            )
            ->orderBy('siswas.nisn') // Order by NISN
            ->get();

        // Function to add rank based on total score
        function addRank($siswas)
        {
            // Rank assignment
            $rankedSiswas = $siswas->sortByDesc('total')->values();
            foreach ($rankedSiswas as $index => $siswa) {
                $siswa->rank = $index + 1;
            }

            // Sort back by NISN for display purposes
            return $rankedSiswas->sortBy('nisn')->values();
        }

        $siswasMIPA = addRank($siswasMIPA);
        $siswasIPS = addRank($siswasIPS);

        // Display the results
        return view('spk.detail', compact('siswasMIPA', 'siswasIPS'));
    }
    
    public function kriteria(Request $request)
    {
        $bobotKriterias = BobotKriteria::all();
        $kuotaSnbps = KuotaSnbp::all();
        
        
        // $siswas = Siswa::join('spk_kriterias', 'siswas.nisn', '=', 'spk_kriterias.nisn')
        //     ->select('siswas.*','spk_kriterias.rapor' , 'spk_kriterias.prestasi' , 'spk_kriterias.sikap', DB::raw('SUM(spk_kriterias.rapor + spk_kriterias.prestasi + spk_kriterias.sikap) AS total'))
        //     ->groupBy('siswas.nisn', 'siswas.nama', 'siswas.kelas_10', 'siswas.kelas_11', 'siswas.kelas_12','siswas.sikap', 'siswas.peminatan','siswas.agama',  'siswas.snbp','siswas.angkatan', 'siswas.updated_at', 'siswas.created_at', 'spk_kriterias.rapor' , 'spk_kriterias.prestasi' , 'spk_kriterias.sikap') // Group by nisn to avoid duplicate rows
        //     ->orderBy('total', 'desc')
        //     ->get();
        // dd($siswas);
        $page = $request->input('page', 1);
        $perPage = 20;

        $queryMIPA = Siswa::join('spk_kriterias', 'siswas.nisn', '=', 'spk_kriterias.nisn')
                    ->where('peminatan', 'MIPA')
                    ->select('siswas.nisn', 'siswas.nama', 'siswas.snbp', 'siswas.kelas_12','spk_kriterias.rapor' , 'spk_kriterias.prestasi' , 'spk_kriterias.sikap', DB::raw('SUM(spk_kriterias.rapor + spk_kriterias.prestasi + spk_kriterias.sikap) AS total'))
                    ->groupBy('siswas.nisn', 'siswas.nama', 'siswas.snbp', 'siswas.kelas_12','siswas.sikap', 'siswas.peminatan','siswas.agama',  'siswas.snbp','siswas.angkatan', 'siswas.updated_at', 'siswas.created_at', 'spk_kriterias.rapor' , 'spk_kriterias.prestasi' , 'spk_kriterias.sikap') // Group by nisn to avoid duplicate rows
                    ->orderBy('total', 'desc');
        // dd($queryMIPA->get());
                    
        

        $queryIPS = Siswa::join('spk_kriterias', 'siswas.nisn', '=', 'spk_kriterias.nisn')
                    ->where('peminatan', 'IPS')
                    ->select('siswas.nisn', 'siswas.nama',  'siswas.kelas_12','spk_kriterias.rapor' , 'spk_kriterias.prestasi' , 'spk_kriterias.sikap', DB::raw('SUM(spk_kriterias.rapor + spk_kriterias.prestasi + spk_kriterias.sikap) AS total'))
                    ->groupBy('siswas.nisn', 'siswas.nama', 'siswas.kelas_12','siswas.sikap', 'siswas.peminatan','siswas.agama',  'siswas.snbp','siswas.angkatan', 'siswas.updated_at', 'siswas.created_at', 'spk_kriterias.rapor' , 'spk_kriterias.prestasi' , 'spk_kriterias.sikap') // Group by nisn to avoid duplicate rows
                    ->orderBy('total', 'desc');
                    

        $startRankMIPA = 1;
        $startRankIPS = 1;

        // Only calculate if the current page is greater than 1
        if ($page > 1) {
            $startRankMIPA = $queryMIPA->get() // Fetch all entries that match
                                        ->take(($page - 1) * $perPage)
                                        ->where('snbp', 'Bersedia')
                                        ->count() // Take records up to the end of the last page
                                        +1;
                                            
        }
        if ($page > 1) {
            $startRankIPS = $queryIPS->get() // Fetch all entries that match
                                    ->take(($page - 1) * $perPage)
                                    ->where('snbp', 'Bersedia')
                                    ->count() // Take records up to the end of the last page
                                    +1;
        }

        // Proceed with other operations, for example, pagination
        $siswasMIPA = $queryMIPA->paginate($perPage, ['*'], 'page', $page);
        $siswasIPS = $queryIPS->paginate($perPage, ['*'], 'page', $page);

    
        return view('spk.kriteria', compact(
            'bobotKriterias','siswasMIPA', 'siswasIPS', 'kuotaSnbps',
            'startRankMIPA',
            'startRankIPS', 
        ));
    }

    public function normalisasi(Request $request)
    {
        $bobotKriterias = BobotKriteria::all();
        $kuotaSnbps = KuotaSnbp::all();
        $page = $request->input('page', 1);
        $perPage = 20;

        $queryMIPA = Siswa::join('spk_normalisasis', 'siswas.nisn', '=', 'spk_normalisasis.nisn')
                    ->where('peminatan', 'MIPA')
                    ->select('siswas.nisn', 'siswas.nama', 'siswas.snbp', 'siswas.kelas_12','spk_normalisasis.rapor' , 'spk_normalisasis.prestasi' , 'spk_normalisasis.sikap', DB::raw('SUM(spk_normalisasis.rapor + spk_normalisasis.prestasi + spk_normalisasis.sikap) AS total'))
                    ->groupBy('siswas.nisn', 'siswas.nama', 'siswas.snbp', 'siswas.kelas_12','siswas.sikap', 'siswas.peminatan','siswas.agama',  'siswas.snbp','siswas.angkatan', 'siswas.updated_at', 'siswas.created_at', 'spk_normalisasis.rapor' , 'spk_normalisasis.prestasi' , 'spk_normalisasis.sikap') // Group by nisn to avoid duplicate rows
                    ->orderBy('total', 'desc');
        // dd($queryMIPA->get());
                    
        

        $queryIPS = Siswa::join('spk_normalisasis', 'siswas.nisn', '=', 'spk_normalisasis.nisn')
                    ->where('peminatan', 'IPS')
                    ->select('siswas.nisn', 'siswas.nama',  'siswas.kelas_12','spk_normalisasis.rapor' , 'spk_normalisasis.prestasi' , 'spk_normalisasis.sikap', DB::raw('SUM(spk_normalisasis.rapor + spk_normalisasis.prestasi + spk_normalisasis.sikap) AS total'))
                    ->groupBy('siswas.nisn', 'siswas.nama', 'siswas.kelas_12','siswas.sikap', 'siswas.peminatan','siswas.agama',  'siswas.snbp','siswas.angkatan', 'siswas.updated_at', 'siswas.created_at', 'spk_normalisasis.rapor' , 'spk_normalisasis.prestasi' , 'spk_normalisasis.sikap') // Group by nisn to avoid duplicate rows
                    ->orderBy('total', 'desc');
                    

        $startRankMIPA = 1;
        $startRankIPS = 1;

        // Only calculate if the current page is greater than 1
        if ($page > 1) {
            $startRankMIPA = $queryMIPA->get() // Fetch all entries that match
                                        ->take(($page - 1) * $perPage)
                                        ->where('snbp', 'Bersedia')
                                        ->count() // Take records up to the end of the last page
                                        +1;
                                            
        }
        if ($page > 1) {
            $startRankIPS = $queryIPS->get() // Fetch all entries that match
                                    ->take(($page - 1) * $perPage)
                                    ->where('snbp', 'Bersedia')
                                    ->count() // Take records up to the end of the last page
                                    +1;
        }

        // Proceed with other operations, for example, pagination
        $siswasMIPA = $queryMIPA->paginate($perPage, ['*'], 'page', $page);
        $siswasIPS = $queryIPS->paginate($perPage, ['*'], 'page', $page);

    
        return view('spk.normalisasi', compact(
            'bobotKriterias','siswasMIPA', 'siswasIPS', 'kuotaSnbps',
            'startRankMIPA',
            'startRankIPS', 
        ));
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

    private function getRataRapor1($nisn): string
    {
        $allRataNilaiP = Rapor::where('nisn', operator: $nisn)->pluck('rata_nilai_p')->toArray();
        
        $rataRapor = (array_sum($allRataNilaiP)/count($allRataNilaiP));
        $rataRapor = number_format($rataRapor, 6);

        return $rataRapor;
    }

    private function getRataRapor($nisn)
    {
        $nilaiPRecords = Rapor::where('nisn', $nisn)
                        ->select('sem_1_nilai_p', 'sem_2_nilai_p', 'sem_3_nilai_p', 'sem_4_nilai_p', 'sem_5_nilai_p')
                        ->get()
                        ->toArray();

        $nilaiPArray = [];

        // Loop through the records and collect all non-null nilai_p values
        foreach ($nilaiPRecords as $record) {
            // Loop through each semester's nilai_p values
            foreach ($record as $nilaiP) {
                if (!is_null($nilaiP)) {
                    $nilaiPArray[] = $nilaiP;
                }
            }
        }

        if (count($nilaiPArray) > 0) {
            $average = array_sum($nilaiPArray) / count($nilaiPArray);
        } else {
            $average = 0;
        }

        return $average;
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

    private function getMaxRapor()
    {
        return SpkKriteria::max('rapor');
    }

    private function getMaxPrestasi()
    {
        return SpkKriteria::max('prestasi');
    }

    private function getMaxSikap()
    {
        return SpkKriteria::max('sikap');
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
