<?php

namespace App\Http\Controllers;

use App\Models\DetalleFactura;
use App\Models\Factura;
use App\Models\User;
use App\Mail\NuevoAdmin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Http\Request;
use App\Enums\Role;


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

        $data = $request->validate([
            'nombre'   => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
        ]);
        $passwordTemporal = Str::password(12);

        try {
            DB::transaction(function () use ($data, $passwordTemporal, &$usuario) {
                $usuario = new User(); 
                $usuario->name = $data['nombre'];
                $usuario->apellido = $data['apellido'];
                $usuario->username = $data['username'];
                $usuario->email = $data['email'];
                $usuario->password =  $passwordTemporal;
                $usuario->role = Role::Admin;
                $usuario->activo = true;
                $usuario->debe_cambiar_password = true;
                $usuario->save();

                Mail::to($usuario->email)->send(new NuevoAdmin($usuario, $passwordTemporal));
            });

        } catch (\Throwable $e) {
            Log::error('Error al crear administrador', [
                'email' => $data['email'],
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
            ->withInput()
            ->with('error', 'No se pudo crear el administrador. Intente de nuevo mas tarde.');
        }
        
        return redirect() ->back() -> with('success', 'Administrador creado exitosamente');
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

    public function update(Request $request) {

        $usuario = $request->user();

        $data = $request->validate([
            'nombre'   => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($usuario->id)],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
            'current_password' => ['required_with:password', 'nullable', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);


        $usuario->name = $data['nombre'];
        $usuario->apellido = $data['apellido'];
        $usuario->username = $data['username'];
        $usuario->email = $data['email'];


        if (!empty($data['password'])) {
            $usuario->password = $data['password']; 
            $usuario->debe_cambiar_password = false;
        }

        $usuario->save();
        return redirect() -> route('usuario.perfil') -> with('success', 'Perfil actualizado correctamente.');

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


    public function transferirSuperAdmin(User $usuario)
    {
        $actual = auth()->user();

        abort_unless($actual->isSuperAdmin(), 403);

        if ($usuario->id === $actual->id) {
            return redirect()->back()->with('error', 'Ya eres el superadministrador.');
        }

        if ($usuario->role !== Role::Admin || !$usuario->activo) {
            return redirect()->back()->with('error', 'Solo se puede transferir el rol a un administrador activo.');
        }

        DB::transaction(function () use ($actual, $usuario) {
            $usuario->role = Role::SuperAdmin;
            $usuario->save();

            $actual->role = Role::Admin;
            $actual->save();
        });

        return redirect()->route('usuario.perfil')
            ->with('success', "{$usuario->name} {$usuario->apellido} es ahora el superadministrador.");
    }

}
