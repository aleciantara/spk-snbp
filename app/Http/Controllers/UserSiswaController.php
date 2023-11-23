<?php

namespace App\Http\Controllers;

use App\Models\BobotKriteria;
use App\Models\KuotaSnbp;
use App\Models\Prestasi;
use App\Models\Rapor;
use App\Models\Siswa;
use App\Models\SpkKriteria;
use App\Models\User;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSiswaController extends Controller
{
    public function index()
    {
        $kuotaSnbps = KuotaSnbp::all();
        $totalSiswa = Siswa::count();
        $siswaCountByPeminatan = Siswa::select('peminatan', DB::raw('count(*) as total'))
            ->groupBy('peminatan')
            ->pluck('total', 'peminatan')
            ->toArray();

        return view('dashboard', compact('kuotaSnbps', 'totalSiswa', 'siswaCountByPeminatan'));
    }
    public function readSiswa()
    {
        $user = Auth::user();// Get the Siswa and related Rapor data by $nisn
        $siswa = Siswa::where('nisn', $user->email)->firstOrFail(); // Fetch the student manually
        return view('siswa.read', ['siswa' => $siswa]);
    }

    public function readRaporSiswa()
    {
        $user = Auth::user();// Get the Siswa and related Rapor data by $nisn
        $siswa = Siswa::where('nisn', $user->email)->firstOrFail(); 
        $raporData = $siswa->rapor;
        $spkKriteria = $siswa->spk_kriteria;
        return view('rapor.read', ['siswa' => $siswa, 'raporData' => $raporData, 'spkKriteria' => $spkKriteria]);
    }

    public function readPrestasiSiswa()
    {
        $user = Auth::user();// Get the Siswa and related Rapor data by $nisn
        //fetch data siswa
        $siswa = Siswa::where('nisn', $user->email)->firstOrFail(); 
        $nisn = $siswa->nisn;

        // Use whereHas to filter Prestasi records based on related PrestasiSiswa records
        $prestasis = Prestasi::whereHas('prestasiSiswa', function ($query) use ($nisn) {
            $query->where('nisn', $nisn);
        })->orderBy('updated_at', 'desc') // Sort by created_at in descending order
        ->paginate(30);

        // Pass the fetched data to the view
        return view('siswa.prestasi', compact('prestasis', 'siswa'));
    }

    public function ajukanPrestasiSiswa()
    {
        $user = Auth::user();// Get the Siswa and related Rapor data by $nisn
        //fetch data siswa
        $siswa = Siswa::where('nisn', $user->email)->firstOrFail(); 
        $nisn = $siswa->nisn;

        $siswas = Siswa::orderBy('nama')->get();
        return view('prestasi.ajukan', compact('siswas','siswa'));
    }


    public function storePengajuanPrestasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama.*' => 'required|distinct',
            'prestasi' => 'required',
            'penyelenggara' => 'required',
            'juara' => 'required',
            'tingkat' => 'required',
            'waktu' => 'required',
            'file' => 'required|mimes:png,jpeg',
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
            'poin' => 0,
            'status' =>'unverified',
        ]);

        // Handle the file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $prestasi->file = $filename;
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

            }
        }
        
        return redirect()->route('prestasi.read')->with('success', 'Prestasi added successfully'); // Default URL if there's no previous URL
    }

    public function readSpk()
    {
        $bobotKriterias = BobotKriteria::all();
        $kuotaSnbps = KuotaSnbp::all();
        

        $siswas = Siswa::join('spk_preferensis', 'siswas.nisn', '=', 'spk_preferensis.nisn')
            ->orderBy('spk_preferensis.total', 'desc')
            ->select('siswas.*')
            ->get();

        return view('spk.index', compact('bobotKriterias', 'siswas', 'kuotaSnbps'));
    }

    public function editPassword(){
        return view('userSiswa/editPassword');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'passwordBaru' => 'required|min:8,',
            'konfirmasiPassword' => 'required|same:passwordBaru,'], 
        );

        if (!$validated){
            return redirect()->back()->with('errors', 'Update Password gagal!');
        }
        else {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('editPassword')->with('error', 'User not found.');
            }
        
            $user->passwordBaru = Hash::make($request->input('passwordBaru'));
            
            User::where('email', $user->email)
                ->update([
                    'password' => $user->passwordBaru,
                ]);
            return redirect()->back()->with('success', 'Password berhasil diupdate!');

        }
        
    }

}
