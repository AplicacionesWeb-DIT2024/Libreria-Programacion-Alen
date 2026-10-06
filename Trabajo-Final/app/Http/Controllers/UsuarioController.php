<?php

namespace App\Http\Controllers;

use App\Models\DetalleFactura;
use App\Models\Factura;
use App\Models\User;
use App\Mail\NuevoAdmin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index() {
        $usuarios = User::query()
        ->orderBy('id')
        ->get();
        return view('listarUsuarios', compact('usuarios'));
    }

    public function index_api() {
        $usuarios = User::orderBy('username', 'asc') -> get();
        return response()->json($usuarios, 200);

    }

    public function show_api(Request $request) {
        $usuario = User::where ('id', $request->id) -> first();

        if ($usuario) {
            return response()->json($usuario, 200);
        } else {
            return response()->json(['message' => 'usuario no encontrado'], 404);
        }



        
    }


    public function show_api_factura_cliente(Request $request) {
        $facturas = Factura::where ('cliente', $request->cliente) ->with('detalles') -> get();
        if ($facturas->isNotEmpty()) {
            return response()->json($facturas, 200);
        } else {
            return response()->json(['message' => 'Facturas no encontradas'], 404);

        }

    }


    public function store(Request $request) {

        $request->validate([
            'nombre'   => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'unique:users,username'],
            'email'    => ['required', 'email', 'unique:users,email'],
        ]);
        $passwordTemporal = Str::password(12);

        $usuario = new User(); 
        $usuario->name = $request->nombre; 
        $usuario->apellido = $request->apellido;
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        $usuario->domicilio = $request->domicilio;
        $usuario->password = Hash::make($passwordTemporal);
        $usuario->is_admin = true;
        $usuario->activo = true;
        $usuario->debe_cambiar_password = true;
        $usuario->save();

        Mail::to($usuario->email)->send(new NuevoAdmin($usuario, $passwordTemporal));

        return redirect() ->back() -> with('success', 'Usuario creado exitosamente');
    }


    public function store_api(Request $request) {
        try {
            $usuario = new User(); 
            $usuario->name = $request->nombre; 
            $usuario->username = $request->username;
            $usuario->email = $request->email;
            $usuario->domicilio = $request->domicilio;
            $usuario->password = bcrypt($request->password);
            $usuario->admin = false;
            $usuario->save();
            return response()->json(['message' => 'Usuario creado exitosamente'], 200);
        }
        catch (\Exception $error) {
            return response()->json(['error' => 'Error al crear el usuario: ' . $error->getMessage()], 500);

        }


    }


    public function create() {
        return view('agregarUsuario');
    }


    public function edit() {
        $usuario = auth()->user();
        return view('verPerfil', compact('usuario') );
    }

    public function update(Request $request, User $usuario) {

        $request->validate([
            'nombre' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'domicilio' => 'nullable|string|max:255',
            'password' => 'nullable|string | confirmed | min:8'
        ]);
        $usuario->name = $request->nombre; 
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        $usuario->domicilio = $request->domicilio;
        if ($request->filled('password')) {
            $usuario->password = $request->password;
            $usuario->debe_cambiar_password = false;
        }

        $usuario->save();
        return redirect() -> route('usuario.perfil') -> with('success', 'Usuario actualizado correctamente.');

    }

    public function delete(User $usuario) {
        if ($usuario->id === auth()->id()) {
            return redirect()->back()->with('error', 'No podés desactivar tu propia cuenta.');
        }
        $usuario->activo = false;
        $usuario->save();
        return redirect() -> route('usuarios.index');
    }

    public function activar(User $usuario)
    {
        $usuario->activo = true;
        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario reactivado correctamente');
    }

}
