<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\Rapor;
use App\Models\Siswa;
use App\Models\PrestasiSiswa;
use App\Models\SpkKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        // Get the search query and status filter from the request
        $searchQuery = $request->input('search');
        $statusFilters = $request->input('status', []); // Default to an empty array

        // Subquery to get distinct prestasi IDs
        $distinctPrestasiIdsQuery = Prestasi::select('prestasis.id')
            ->join('prestasi_siswa', 'prestasis.id', '=', 'prestasi_siswa.prestasi_id')
            ->join('siswas', 'prestasi_siswa.nisn', '=', 'siswas.nisn')
            ->where(function ($query) use ($searchQuery) {
                $query->where('prestasis.prestasi', 'like', "%$searchQuery%")
                    ->orWhere('siswas.nama', 'like', "%$searchQuery%");
            })
            ->distinct();

        // Apply status filter if any status is selected
        if (!empty($statusFilters)) {
            $distinctPrestasiIdsQuery->whereIn('prestasis.status', $statusFilters);
        }

        $distinctPrestasiIds = $distinctPrestasiIdsQuery->pluck('prestasis.id');


        $distinctPrestasiIds = $distinctPrestasiIdsQuery->pluck('prestasis.id');

        // Fetch the prestasi using the distinct IDs and paginate
        $prestasis = Prestasi::with('prestasiSiswa.siswas')
            ->whereIn('id', $distinctPrestasiIds)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        // dd($prestasis);

        // Pass the fetched data to the view
        return view('prestasi.index', compact('prestasis'));
    }

    public function create($siswa = null)
    {
        session(['previous_url' => url()->previous()]);

        if (!empty($siswa)) {
            $siswa = Siswa::where('nisn', $siswa)->firstOrFail();
        }
        $siswas = Siswa::orderBy('nama')->get();
        return view('prestasi.create', compact('siswas','siswa'));
    }

    public function store(Request $request)
    {
        $previousUrl = session('previous_url');

        // Remove the stored value from the session
        session()->forget('previous_url');

        $validator = Validator::make($request->all(), [
            'nama.*' => 'required|distinct',
            'prestasi' => 'required',
            'penyelenggara' => 'required',
            'juara' => 'required',
            'tingkat' => 'required',
            'waktu' => 'required',
            'poin' => 'required|numeric',
            'file' => 'required|mimes:png,jpeg,pdf',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $prestasi = new Prestasi([
            'prestasi' => $request->input('prestasi'),
            'penyelenggara' => $request->input('penyelenggara'),
            'juara' => $request->input('juara'),
            'tingkat' => $request->input('tingkat'),
            'waktu' => $request->input('waktu'),
            'poin' => $request->input('poin'),
            'status' =>'verified',
        ]);

        // Handle the file upload
        if ($request->hasFile('file')) {
            $fileExtension = $request->file('file')->getClientOriginalExtension();

            // Store the file in the public/templates directory
            $filePath = $request->file('file')->storeAs('public/prestasis', "prestasi_{$request->input('prestasi')}.{$fileExtension}");
            $prestasi->file = "prestasi_{$request->input('prestasi')}.{$fileExtension}";
        }
    
        // Save the Prestasi
        $prestasi->save();

        $namaValues = $request->input('nama');
        if ($namaValues) {
            foreach ($namaValues as $nisn) {
                $siswa = Siswa::where('nisn', $nisn)->first();
                if ($siswa) {
                    // Associate this Siswa with the Prestasi
                    $prestasi->siswas()->attach($siswa);
                }

                $this->storeSpkKriteria($nisn);
            }
        }

        if ($previousUrl) {
            return redirect($previousUrl)->with('success', 'Prestasi berhasil ditambahkan');
        } else {
            return redirect()->route('prestasi.index'); // Default URL if there's no previous URL
        }
    
    }

    public function downloadGambar($id)
    {
        $prestasi = Prestasi::findOrFail($id);

        $download_path = ( public_path() . '\\storage\\prestasis\\' . $prestasi->file );
        return( Response::download( $download_path ) );
    }

    public function edit($id)
    {
         // Store the previous URL in the session
        session(['previous_url' => url()->previous()]);

        $prestasi = Prestasi::with('siswas')->findOrFail($id);
        $siswas = Siswa::all(); 
        // dd($prestasi);
        return view('prestasi.edit', compact('prestasi', 'siswas'));
    }

    public function update(Request $request, $id)
    {
        $previousUrl = session('previous_url');

        // Remove the stored value from the session
        session()->forget('previous_url');
        
       // Validate the incoming request data
        $this->validate($request, [
            'nama' => 'required|array', 
            'prestasi' => 'required|string',
            'penyelenggara' => 'required|string',
            'juara' => 'required|string',
            'tingkat' => 'required|string',
            'waktu' => 'required|date',
            'poin' => 'required|numeric',
            'file' => 'nullable|file|mimes:jpeg,png,pdf|max:2048', 
            'status' => 'required|string',
        ]);

        try {
            // Find the Prestasi record to be updated
            $prestasi = Prestasi::findOrFail($id);

            // Access the 'siswas' relationship and pluck the 'nisn' values
            $existingSiswa = $prestasi->siswas->pluck('nisn');

            // Update the basic Prestasi information
            $prestasi->prestasi = $request->input('prestasi');
            $prestasi->penyelenggara = $request->input('penyelenggara');
            $prestasi->juara = $request->input('juara');
            $prestasi->tingkat = $request->input('tingkat');
            $prestasi->waktu = $request->input('waktu');
            $prestasi->status = $request->input('status');

            // Set poin based on status
            if ($request->input('status') === 'denied' || $request->input('status') === 'unverified') {
                $prestasi->poin = 0;
            } else {
                $prestasi->poin = $request->input('poin');
            }

            if ($request->hasFile('file')) {
                $filePath = public_path("storage\\prestasis\\{$prestasi->file}");
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }

                $fileExtension = $request->file('file')->getClientOriginalExtension();

            // Store the file in the public/templates directory
            $filePath = $request->file('file')->storeAs('public/prestasis', "prestasi_{$request->input('prestasi')}.{$fileExtension}");
            $prestasi->file = "prestasi_{$request->input('prestasi')}.{$fileExtension}";
            }

            // Save the updated Prestasi record
            $prestasi->save();

            // Update the related Siswa records, assuming you have a pivot table named prestasi_siswa
            $prestasi->siswas()->sync($request->input('nama'));

            // Fetch all NISN values related to the Prestasi (both existing and newly attached)
            $allNISNValues = $existingSiswa->merge($request->input('nama'))->unique();

            // Update SPK Kriterias for each NISN
            foreach ($allNISNValues as $nisn) {
                $this->storeSpkKriteria($nisn);
            }
            
            if ($previousUrl) {
                return redirect($previousUrl)->with('success', 'Prestasi berhasil diupdate');
            } else {
                return redirect()->route('prestasi.index'); // Default URL if there's no previous URL
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating Prestasi: ' . $e->getMessage());
        }
    }

    public function destroy(Prestasi $prestasi)
    {
        $prestasi = Prestasi::find($prestasi->id);

        // check if the image indeed exists
        $filePath = public_path("storage\\prestasis\\{$prestasi->file}");
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
            
        $prestasi->delete();

        return redirect()->back()->with('success', 'Prestasi berhasil dihapus');
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
