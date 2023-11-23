<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        }else{
            return redirect('')->withErrors('Username dan Password tidak sesuai')->withInput();
        }
    }

    function logout(){
        Auth::logout();
        return redirect('/');
    }

    
}
 