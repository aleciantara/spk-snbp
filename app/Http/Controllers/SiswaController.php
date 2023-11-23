<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\SpkKriteria;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Rapor;
use App\Imports\ImportRaporSiswa;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    // Display a list of students
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kelasXFilters = $request->input('kelasX', []);
        $kelasXIFilters = $request->input('kelasXI', []);
        $kelasXIIFilters = $request->input('kelasXII', []);

        // dd($kelasXFilters,$kelasXIFilters,$kelasXIIFilters);

        $siswas = Siswa::when($search, function ($query) use ($search) {
            $query->where('nama', 'like', '%' . $search . '%')
            ->orWhere('nisn', 'like', '%' . $search . '%');
        })->when(!empty($kelasXFilters), function ($query) use ($kelasXFilters) {
            $query->whereIn('kelas_10', $kelasXFilters);
        })->when(!empty($kelasXIFilters), function ($query) use ($kelasXIFilters) {
            $query->whereIn('kelas_11', $kelasXIFilters);
        })->when(!empty($kelasXIIFilters), function ($query) use ($kelasXIIFilters) {
            $query->whereIn('kelas_12', $kelasXIIFilters);
        })->orderBy('updated_at', 'desc') // Sort by created_at in descending order
        ->paginate(30);

        return view('siswa.index', compact('siswas'));
    }

    
    public function create()
    {
        return view('siswa.create');
    }

    // Store a newly created student in the database
    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|integer|unique:siswas,nisn',
            'nama' => 'required|string|max:255|regex:/^[A-Za-z\s\-]+$/u',
            'kelas_10' => 'required|string',
            'kelas_11' => 'required|string',
            'kelas_12' => 'required|string',
            'peminatan' => 'required|in:MIPA,IPS',
            'sikap' => 'required|in:Sangat Baik,Baik,Cukup,Buruk,Sangat Buruk',
            'snbp' => 'required|in:Bersedia,Tidak Bersedia',
        ]);

        
        // Check if the current month is between August and March
        $currentYear = date('Y');
        $currentMonth = date('m');

        // Check if the current month is after March (the graduation month)
        if ($currentMonth > 3) {
            // If it's after March, the student will graduate next year
            $graduationYear = $currentYear + 1;
        } else {
            // If it's March or earlier, the student will graduate in the current year
            $graduationYear = $currentYear;
        }

        // Create a new Siswa instance and fill it with the validated data
        $siswa = new Siswa([
            'nisn' => $request->input('nisn'),
            'nama' => $request->input('nama'),
            'kelas_10' => $request->input('kelas_10'),
            'kelas_11' => $request->input('kelas_11'),
            'kelas_12' => $request->input('kelas_12'),
            'peminatan' => $request->input('peminatan'),
            'sikap'     => $request->input('sikap'),
            'snbp'     => $request->input('snbp'),
            'angkatan' => $graduationYear,
        ]);

        // dd($request, $siswa);

        // Save the Siswa instance to the database
        $siswa->save();
                
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
        $selectedSubjects = [];
        $selectedPeminatan = $request->input('peminatan');
        if ($selectedPeminatan == 'MIPA') {
            $selectedSubjects = ['Matematika (minat)', 'Biologi', 'Fisika', 'Kimia'];
        } elseif ($selectedPeminatan == 'IPS') {
            $selectedSubjects = ['Geografi', 'Sejarah (minat)', 'Sosiologi', 'Ekonomi'];
        }

        // Merge "wajib" and selected subjects
        $subjectsToSave = array_merge($wajibSubjects, $selectedSubjects);

        foreach ($subjectsToSave as $subject) {
            // Determine the "jenis" based on whether it's a "wajib" or "peminatan" subject
            $jenis = in_array($subject, $wajibSubjects) ? 'wajib' : 'peminatan';

            $rapor = new Rapor([
                'nisn' => $request->input('nisn'),
                'pelajaran' => $subject,
                'jenis' => $jenis,
            ]);

            $rapor->save();
        }

        $this->storeSpkKriteria($request->input('nisn'));

        // Optionally, you can redirect to a success page or show a success message
        return redirect()->route('rapor.edit', ['nisn' => $request->nisn])->with('success', 'Siswa data has been saved successfully.');
    }


    // Display the specified student   
    public function edit($nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->firstOrFail(); // Fetch the student manually
        return view('siswa.edit', ['siswa' => $siswa]);
    }
    
    
    public function update(Request $request, Siswa $siswa)
    {
        // Determine the old and new peminatan values
        $oldPeminatan = $request->input('old_peminatan');
        // dd($request);
        $newPeminatan = $request->peminatan;

        // dd($oldPeminatan, $newPeminatan);

        // Validate and update the student data
        $request->validate([
            'nama' => 'required|string',
            'kelas_10' => 'nullable|string',
            'kelas_11' => 'nullable|string',
            'kelas_12' => 'nullable|string',
            'peminatan' => 'required|in:MIPA,IPS',
            'sikap' => 'required|in:Sangat Baik,Baik,Cukup,Buruk,Sangat Buruk',
            'snbp' => 'required|in:Bersedia,Tidak Bersedia',
        ]);

        Siswa::where('nisn', $request->nisn)
            ->update([
            'nama' => $request->nama,
            'kelas_10' => $request->kelas_10,
            'kelas_11' => $request->kelas_11,
            'kelas_12' => $request->kelas_12,
            'peminatan' => $request->peminatan,
            'sikap'     => $request->sikap,
            'snbp'     => $request->snbp,
        ]);

        if ($oldPeminatan != $newPeminatan) {

            // Renew Peminatan Mapel Rapor
            $subjectMapping = [
                'MIPA' => ['Matematika (minat)', 'Biologi', 'Fisika', 'Kimia'],
                'IPS' => ['Geografi', 'Sejarah (minat)', 'Sosiologi', 'Ekonomi'],
            ];

            // Update the pelajaran attribute in the rapors table
            foreach ($subjectMapping[$oldPeminatan] as $oldSubject) {
                $newSubject = $subjectMapping[$newPeminatan][array_search($oldSubject, $subjectMapping[$oldPeminatan])];
                // dd($oldSubject,$newSubject);
                
                Rapor::where('nisn', $request->nisn)
                    ->where('pelajaran', $oldSubject)
                    ->update(['pelajaran' => $newSubject]);
            }
        }

        $this->storeSpkKriteria($request->input('nisn'));

        // Fetch the updated student data
        $siswa = Siswa::where('nisn', $request->nisn)->firstOrFail();

        return view('siswa.edit', ['siswa' => $siswa])->with('success', 'Student updated successfully');
    }


    public function import(Request $request)
    {
        $this->validate($request, [
            'excel_file'   => 'required',
            'kelasBerapa'  => 'required',
            'peminatanApa' => 'required',
            'kelasApa'     => 'required',
            'semester'     => 'required',
        ]);
        
        $extension = strtolower($request->file('excel_file')->getClientOriginalExtension());

        if ($extension != 'xls' && $extension != 'xlsx') {
            return redirect()->back()->with('error', 'Impor file Excel harus format xls, xlsx');
        }else{
            if ($request->semester === "Genap" && $request->kelasBerapa === "XII") {
                // dd('condition met');
                return redirect()->back()->with('error', 'Nilai Kelas XII Semester Genap tidak diperhitungkan dalam seleksi eligible SNBP ');
            } else {
                // The semester meets the requirement
                $file = $request->file('excel_file');
                $kelasBerapa = $request->kelasBerapa;
                $peminatanApa = $request->peminatanApa;
                $kelasApa = $request->kelasApa;
                $semesterApa = $request->semester;
        
                // Import the data using your custom import class
                Excel::import(new ImportRaporSiswa($kelasBerapa, $peminatanApa, $kelasApa, $semesterApa), $file);
        
                return redirect()->back()->with('success', 'Data imported successfully!');   
            }
        }

    }

    public function prestasi($nisn)
    {   
        //fetch data siswa
        $siswa = Siswa::where('nisn', $nisn)->firstOrFail(); 

        // Use whereHas to filter Prestasi records based on related PrestasiSiswa records
        $prestasis = Prestasi::whereHas('prestasiSiswa', function ($query) use ($nisn) {
            $query->where('nisn', $nisn);
        })->orderBy('updated_at', 'desc') // Sort by created_at in descending order
        ->paginate(30);
    
        // Pass the fetched data to the view
        return view('siswa.prestasi', compact('prestasis', 'siswa'));
    }

    
    public function destroy(Siswa $siswa)
    {

        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Student deleted successfully');
    }

    public function storeSpkKriteria($nisn)
    {
        //get average rapor
        $rataRapor = $this->getRataRapor($nisn);

        $poinPrestasi = $this->getpoinPrestasi($nisn);

        $sikap = $this->getSpkSikap($nisn);

        // dd($sikap, $rataRapor, $nisn); 

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


}
