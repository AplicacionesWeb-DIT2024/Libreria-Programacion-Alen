<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function show() {
        return view('login');
    }

    public function loguear(Request $request) {
        $credentials = $request -> validate ( [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

       
        if (auth() -> attempt( $credentials)) {
            return redirect()->route('home');
        }
        else {
            return redirect()->back()->withErrors(['error' => 'Nombre de usuario o contraseña incorrectos']);
        }

    }


    public function desloguear(Request $request) {
        Auth::logout(); 
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
