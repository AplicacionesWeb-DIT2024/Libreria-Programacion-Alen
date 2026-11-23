<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cliente;
use App\Enums\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegistroClienteRequest;


class AuthApiController extends Controller
{

    public function registrarApi(RegistroClienteRequest $request)
    {
        $datos = $request->validated();

        $user = DB::transaction(function () use ($datos) {
            $user = new User();
            $user->name = $datos['nombre'];
            $user->apellido = $datos['apellido'];
            $user->username = $datos['username'];
            $user->email = $datos['email'];
            $user->password = Hash::make($datos['password']);
            $user->role = Role::Cliente;
            $user->activo = true;
            $user->save();

            $user->usuario_creacion = $user->id;
            $user->usuario_modificacion = $user->id;
            $user->save();

            $cliente = new Cliente();
            $cliente->user_id = $user->id;
            $cliente->fecha_nacimiento = $datos['fecha_nacimiento'];
            if (!empty($datos['telefono'])) {
                $prefijo = config("paises_telefono.{$datos['pais_telefono']}.prefijo");
                $cliente->pais_telefono = $datos['pais_telefono'];
                $cliente->telefono = $prefijo . $datos['telefono'];
            } else {
                $cliente->pais_telefono = null;
                $cliente->telefono = null;
            }
            $cliente->domicilio_calle = $datos['domicilio_calle'];
            $cliente->domicilio_numero = $datos['domicilio_numero'];
            $cliente->domicilio_localidad = $datos['domicilio_localidad'];
            $cliente->domicilio_provincia = $datos['domicilio_provincia'];
            $cliente->domicilio_codigo_postal = $datos['domicilio_codigo_postal'];
            $cliente->save();

            return $user;
        });

        $token = $user->createToken('react')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user->load('cliente'),
        ], 201);
    }


    public function loguearApi(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Ingrese su nombre de usuario.',
            'password.required' => 'Ingrese su contraseña.',
        ]);

        $user = User::whereRaw('LOWER(username) = ?', [mb_strtolower(trim($credentials['username']))])
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
