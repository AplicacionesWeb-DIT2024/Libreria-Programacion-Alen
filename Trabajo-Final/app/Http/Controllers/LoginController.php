<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
