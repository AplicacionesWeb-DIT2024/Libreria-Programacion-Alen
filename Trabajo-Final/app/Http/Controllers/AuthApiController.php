<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    public function loguearApi(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $credentials['username'])
            ->where('activo', true)
            ->where('role', Role::Cliente)
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Usuario o contraseña incorrectos'], 401);
        }

        $token = $user->createToken('react')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => $user->load('cliente'),
        ], 200);
    }


    public function desloguearApi(Request $request) {
       $request->user()->currentAccessToken()->delete();

       return response()->json(['message' => 'Sesión cerrada'], 200);
    }
}
