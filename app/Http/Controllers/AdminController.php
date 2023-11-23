<?php

namespace App\Http\Controllers;

use App\Models\KuotaSnbp;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
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

    public function editPassword(){
        return view('admin/editPassword');
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
            
            User::where('name', $user->name)
                ->update([
                    'password' => $user->passwordBaru,
                ]);
            return redirect()->back()->with('success', 'Password berhasil diupdate!');

        }
        
    }
    
}
