<?php
namespace app\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use App\Models\Siswa;
use App\Models\Rapor;
use App\Models\SpkKriteria;// Make sure the model path is correct

HeadingRowFormatter::default('none');

class ImportRaporSiswa implements ToModel, WithStartRow, WithHeadingRow
{
    
    public function startRow(): int
    {
        return 8; // Specify the starting row for data extraction
    }

    private $kelasBerapa;
    private $kelasApa;
    private $semesterApa;
    private $peminatanApa;

    public function __construct($kelasBerapa, $peminatanApa, $kelasApa, $semesterApa)
    {
        $this->kelasBerapa = $kelasBerapa;
        $this->kelasApa = $kelasApa;
        $this->semesterApa = $semesterApa;
        $this->peminatanApa = $peminatanApa;
    }

    public function model(array $row)
    {
        // Extract relevant data from the $row array
        // dd($row);
        $nisn = $row[2];
        $nama = $row[1];
        $semester = $this->getSemester($this->kelasBerapa, strtolower($this->semesterApa));
        $kelas = $this->kelasBerapa . ' - ' . $this->peminatanApa . ' ' . $this->kelasApa;
        $peminatan = $this->peminatanApa;
        $agama = $row[3];
        $angkatan = $this->getAngkatan();

        // Check if the "siswa" record with the given NISN exists
        $siswa = Siswa::where('nisn', $nisn)->first();

        if (!$siswa) {
            // If $siswa is null, create a new Siswa object
            $this->storeSiswa($nisn, $nama, $peminatan, $agama, $angkatan, $kelas);

            // Create blank Rapor for siswa
            $this->createBlankRapor($nisn, $peminatan);

            
        } else {
            //save kelas siswa
            $kelasField = $this->getKelasField($kelas);
    
            if ($kelasField) {
                $siswa->$kelasField = $kelas;
            }

            // Save the updated Siswa object
            $siswa->save();
        }
        
        //store data rapor
        $this->storeRapor($semester, $nisn, $row);
        // Store SPK Kriteria
        $this->storeSpkKriteria($nisn);

    }

    private function storeSiswa($nisn, $nama, $peminatan, $agama, $angkatan, $kelas)
    {
        $siswa = new Siswa([
            'nisn' => $nisn,
            'nama' => $nama,
            'sikap' => 'Baik',
            'peminatan' => $peminatan,
            'agama' => $agama,
            'angkatan' => $angkatan,
        ]);
    
        // Use the getKelasField function to determine the field name
        $kelasField = $this->getKelasField($kelas);
    
        if ($kelasField) {
            $siswa->$kelasField = $kelas;
        }
    
        // dd($siswa);
        $siswa->save();
    }

    private function createBlankRapor($nisn, $peminatan)
    {
         // Create a list of subjects that are "wajib"
        $wajibSubjects = [
            'Pendidikan Agama dan Budi Pekerti',
            'Pendidikan Pancasila dan Kewarganegaraan',
            'Bahasa Indonesia',
            'Matematika',
            'Sejarah Indonesia',
            'Bahasa Inggris',
            'Seni budaya',
            'Prakarya dan Kewirausahaan',
            'Pendidikan Jasmani Olahraga dan Kesehatan',
        ];

        // Create an array of subjects based on the selected "peminatan"
        $peminatanSubjects = [];
        if ($peminatan == 'MIPA') {
            $peminatanSubjects = ['Matematika (minat)', 'Biologi', 'Fisika', 'Kimia'];
        } elseif ($peminatan == 'IPS') {
            $peminatanSubjects = ['Geografi', 'Sejarah (minat)', 'Sosiologi', 'Ekonomi'];
        }

        // Merge "wajib" and selected subjects
        $subjectsToSave = array_merge($wajibSubjects, $peminatanSubjects);

        foreach ($subjectsToSave as $subject) {
            // Determine the "jenis" based on whether it's a "wajib" or "peminatan" subject
            $jenis = in_array($subject, $wajibSubjects) ? 'wajib' : 'peminatan';

            $rapor = new Rapor([
                'nisn' => $nisn,
                'pelajaran' => $subject,
                'jenis' => $jenis,
            ]);

            $rapor->save();
        }
    }

    private function storeRapor($semester, $nisn, $row)
    {
        // Get all the records for the subject and semester
        $records = Rapor::where('nisn', $nisn)
                        ->get();
                    

        $arrayCount = count($records);
        // dd($row, $arrayCount, $records);
        

        for ($index = 0; $index <= $arrayCount; $index++) {
            $raporData = [
                'nisn' => $nisn,
                'pelajaran'     => $records[$index]['pelajaran'] ?? null,
                'sem_1_nilai_p' => $records[$index]['sem_1_nilai_p'] ?? null,
                'sem_2_nilai_p' => $records[$index]['sem_2_nilai_p'] ?? null,
                'sem_3_nilai_p' => $records[$index]['sem_3_nilai_p'] ?? null,
                'sem_4_nilai_p' => $records[$index]['sem_4_nilai_p'] ?? null,
                'sem_5_nilai_p' => $records[$index]['sem_5_nilai_p'] ?? null,
            ];

            $raporData['sem_' . $semester . '_nilai_p'] = $row[$index + 6];

            if ($index == 0){
                $raporData['sem_' . $semester . '_nilai_p'] = $this->getNilaiAgama($row);
            }
        
            // Calculate rata-rata (average) for nilai_P
            $filledPNilaiCount = 0;
            $totalPNilai = 0;

            for ($i = 1; $i <= 5; $i++) {
                if (!empty($raporData["sem_" . $i . "_nilai_p"])) {
                    $totalPNilai += $raporData["sem_" . $i . "_nilai_p"];
                    $filledPNilaiCount++;
                }
            }

            if ($filledPNilaiCount >= 1) {
                // At least 1 fields are filled for P, calculate the average
                $averagePNilai = $totalPNilai / $filledPNilaiCount;
            } else {
                $averagePNilai = null; // Set to null if less than 1 are filled
            }

            // Update the corresponding rapor data based on $nisn and $pelajaran
            Rapor::where('nisn', $nisn)
            ->where('pelajaran', $raporData['pelajaran'])
            ->update([
                'sem_1_nilai_p' => $raporData['sem_1_nilai_p'],
                'sem_2_nilai_p' => $raporData['sem_2_nilai_p'],
                'sem_3_nilai_p' => $raporData['sem_3_nilai_p'],
                'sem_4_nilai_p' => $raporData['sem_4_nilai_p'],
                'sem_5_nilai_p' => $raporData['sem_5_nilai_p'],
                'rata_nilai_p' => $averagePNilai,
            ]);
        }
    }

    private function getNilaiAgama($row)
    {
        if (!empty($row[4])){
            return $row[4];
        }elseif (!empty($row[5])){
            return $row[5];
        }elseif (!empty($row[6])){
            return $row[6];
        }else{
            return null;
        }
    }

    private function storeSpkKriteria($nisn)
    {
        //get average rapor
        $rataRapor = $this->getRataRapor($nisn);

        $sikap = $this->getSpkSikap($nisn);

        // dd($sikap, $rataRapor, $nisn); 

        SpkKriteria::updateOrCreate(
            ['nisn' => $nisn],
            ['rapor' => $rataRapor, 'sikap' => $sikap]
        );
        

    }

    private function getRataRapor($nisn)
    {
        $allRataNilaiP = Rapor::where('nisn', $nisn)->pluck('rata_nilai_p')->toArray();
        
        $rataRapor = (array_sum($allRataNilaiP)/count($allRataNilaiP));
        $rataRapor = number_format($rataRapor, 2);

        return $rataRapor;
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

    private function getSemester($kelas, $semester)
    {

        $semesterNumber = 0; // Default to 0 (not found)

        if ($semester === "ganjil" && $kelas === "X") {
            $semesterNumber = 1; 
        } elseif ($semester === "genap" && $kelas === "X") {
            $semesterNumber = 2; 
        } elseif ($semester === "ganjil" && $kelas === "XI") {
            $semesterNumber = 3; 
        } elseif ($semester === "genap" && $kelas === "XI") {
            $semesterNumber = 4; 
        } elseif ($semester === "ganjil" && $kelas === "XII") {
            $semesterNumber = 5; 
        } elseif ($semester === "genap" && $kelas === "XII") {
            // dd('This condition is met');
            $semesterNumber = 6;
        }

        return $semesterNumber;
    }

    private function getAngkatan()
    {
        $angkatan = '';
        // Check if the current month is between August and March
        $currentYear = date('Y');
        $currentMonth = date('m');

        if ($currentMonth > 3) {
            // If it's after March, the student will graduate next year
            $angkatan = $currentYear + 1;
        } else {
            // If it's March or earlier, the student will graduate in the current year
            $angkatan = $currentYear;
        }

        return $angkatan;
    }

    private function getKelasField($kelas)
    {
        $kelasPart = current(explode(' ', $kelas));
    
        if ($kelasPart == 'X') {
            return 'kelas_10';
        } elseif ($kelasPart == 'XI') {
            return 'kelas_11';
        } elseif ($kelasPart == 'XII') {
            return 'kelas_12';
        }
    
        return null; // Return null or handle other cases if needed
    }


    
}
