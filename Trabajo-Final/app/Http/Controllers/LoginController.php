<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Enums\Role;


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

        $ok = Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'activo'   => true,
            fn ($query) => $query->whereIn('role', [Role::Admin->value, Role::SuperAdmin->value]),
        ]);

        if ($ok) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return redirect()->back()
        ->withErrors(['error' => 'Nombre de usuario o contraseña incorrectos.']);

    }


    public function desloguear(Request $request) {
        Auth::logout(); 
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
