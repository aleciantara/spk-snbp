<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SessionController extends Controller
{
    function index(){
        return view('login');
    }

    function login(Request $request){
        $request ->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'email wajib diisi',
            'password.required' => 'password wajib diisi',
        ]);

        $infoLogin = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (Auth::attempt($infoLogin)){
            return redirect('/dashboard'); 
        }else {
            // Check if the username/email exists in the database
            $userExists = User::where('email', $infoLogin['email'])->exists();
        
            if ($userExists) {
                // If the user exists but the password is incorrect
                return redirect()->back()->withErrors('Username dan Password tidak sesuai')->withInput();
            } else {
                // If the user does not exist in the database
                return redirect()->back()->withErrors('Username tidak terdaftar dalam sistem')->withInput();
            }
        }
    }

    function logout(){
        Auth::logout();
        return redirect('/');
    }
}