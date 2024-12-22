<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;  
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Siswa;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');
        $roleFilters = $request->input('role', []);

        $query = User::query()->orderBy('updated_at', 'desc');

        // Apply search condition
        if ($searchQuery) {
            $query->where(function ($subQuery) use ($searchQuery) {
                $subQuery->where('users.email', 'like', "%$searchQuery%")
                    ->orWhere('users.name', 'like', "%$searchQuery%"
                );
            });
        }

        // Apply role filters
        if (!empty($roleFilters)) {
            $query->whereIn('role', $roleFilters);
        }
        
        // Fetch the data with pagination
        $users = $query->paginate(30);

        return view('user.index', compact('users'));
    }

    public function storeAdmin(Request $request)
    {
        // dd($request);
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'passwordBaru' => 'required|min:8',
            'konfirmasiPassword' => 'required|same:passwordBaru,'], 
        );

        if (!$validated){
            return redirect()->back()->with('error', 'Gagal Tambah Admin!');
        }else{

            // Create a new admin user
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('passwordBaru'));
            $user->role = 'admin'; 
            $user->save();

            return redirect()->route('user.index')->with('success', 'User Admin berhasil ditambahkan');
        }
    }

    public function updatePassword(Request $request)
    {
        // dd($request);
        // Validate the incoming request data
        $validated = $request->validate([
            'passwordBaru' => 'required|min:8',
            'konfirmasiPassword' => 'required|same:passwordBaru,' 
        ]);

        if (!$validated){
            return redirect()->route('user.index')->with('error', 'Update Password Gagal');
        }else{

            // Find the user by ID
            $user = User::findOrFail($request->input('id'));

            // Update the user's password
            $user->password = Hash::make($request->input('passwordBaru'));
            $user->save();

            return redirect()->route('user.index')->with('success', 'Password berhasil diupdate');
        }
    }

    public function destroyUser(User $user)
    {

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }

    public function generateAkunSiswa()
    {
        // Fetch all data from siswas
        $siswas = Siswa::all();

        // Counter for new accounts
        $newAccountsCount = 0;

        foreach ($siswas as $siswa) {
            // Check if NISN siswa already appears in users.email
            $existingUser = User::where('email', $siswa->nisn)->first();

            if ($existingUser) {
                // Skip to the next siswa
                continue;
            }

            // Check if rapor siswa with pelajaran == 'matematika' has non-null values in nilai_p_sem_1 - 5
            $raporMatematika = $siswa->rapor()->where('pelajaran', 'matematika')->first();

            if ($raporMatematika &&
                $raporMatematika->sem_1_nilai_p !== null &&
                $raporMatematika->sem_2_nilai_p !== null &&
                $raporMatematika->sem_3_nilai_p !== null &&
                $raporMatematika->sem_4_nilai_p !== null &&
                $raporMatematika->sem_5_nilai_p !== null) {
                // Create new user with role siswa
                $password = $this->generatePassword($raporMatematika);
                // dd($password);
                
                User::create([
                    'name' => $siswa->nama,
                    'email' => $siswa->nisn,
                    'password' => Hash::make($password),
                    'role' => 'siswa',
                ]);
                
                $newAccountsCount++;
            }
        }

        $message = "$newAccountsCount akun siswa berhasil di-generate";
        return redirect()->route('user.index')->with('success', $message);
    }

    // Helper function to generate a password based on matematika nilai from sem 1-3
    private function generatePassword($raporMatematika)
    {
        $sem_1_nilai_p = (int)($raporMatematika->sem_1_nilai_p);
        $sem_2_nilai_p = (int)($raporMatematika->sem_2_nilai_p);
        $sem_3_nilai_p = (int)($raporMatematika->sem_3_nilai_p);

        // Concatenate the integer values
        $password = $sem_1_nilai_p . $sem_2_nilai_p . $sem_3_nilai_p;

        return $password;
    }

    
}
