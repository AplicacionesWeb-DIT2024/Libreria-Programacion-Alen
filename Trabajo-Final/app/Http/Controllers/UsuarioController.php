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
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Http\Request;
use App\Enums\Role;


class UsuarioController extends Controller
{
    public function indexAdmins()
    {
        $usuarios = User::whereIn('role', [Role::Admin, Role::SuperAdmin])
            ->orderBy('id')
            ->get();
        return view('listarAdministradores', compact('usuarios'));
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

        $request->validateWithBag('agregarAdministrador', [
            'nombre' => ['required', 'string', 'max:255', "regex:/^[\p{L}][\p{L}\s'’.-]*$/u"],
            'apellido' => ['required', 'string', 'max:255', "regex:/^[\p{L}][\p{L}\s'’.-]*$/u"],
            'username' => [
                'required', 'string', 'min:4', 'max:50',
                'regex:/^[A-Za-z0-9._-]+$/',
                function ($attribute, $value, $fail) {
                    if (User::whereRaw('LOWER(username) = ?', [mb_strtolower(trim($value))])->exists()) {
                        $fail('Ya existe un usuario con ese nombre de usuario.');
                    }
                },
            ],
            'email' => [
                'required', 'email:rfc', 'max:255','regex:/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/',
                function ($attribute, $value, $fail) {
                    if (User::whereRaw('LOWER(email) = ?', [mb_strtolower(trim($value))])->exists()) {
                        $fail('Ya existe un usuario con ese email.');
                    }
                },
            ],
        ], [
            'required'       => 'Este campo es obligatorio.',
            'max'            => 'No puede superar los :max caracteres.',
            'username.min'   => 'El usuario debe tener al menos :min caracteres.',
            'nombre.regex'   => 'El nombre solo puede contener letras, espacios, apóstrofes y guiones.',
            'apellido.regex' => 'El apellido solo puede contener letras, espacios, apóstrofes y guiones.',
            'username.regex' => 'El usuario solo puede contener letras, números, puntos, guiones y guiones bajos.',
            'email.email'    => 'El email no es válido.',
            'email.regex'    => 'El email no es válido.'
        ]);

        $data = $request->only(['nombre', 'apellido', 'username', 'email']);
        $data['email'] = mb_strtolower(trim($data['email']));
        $data['username'] = trim($data['username']);
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
        'nombre'   => ['required', 'string', 'max:255', "regex:/^[\p{L}][\p{L}\s'’.-]*$/u"],
        'apellido' => ['required', 'string', 'max:255', "regex:/^[\p{L}][\p{L}\s'’.-]*$/u"],
        'username' => [
            'required', 'string', 'min:4', 'max:50',
            'regex:/^[A-Za-z0-9._-]+$/',
            function ($attribute, $value, $fail) use ($usuario) {
                $existe = User::whereRaw('LOWER(username) = ?', [mb_strtolower(trim($value))])
                    ->where('id', '!=', $usuario->id)
                    ->exists();
                if ($existe) {
                    $fail('Ya existe una persona con ese nombre de usuario.');
                }
            },
        ],
        'email' => [
            'required', 'email:rfc', 'max:255',
            'regex:/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/',
            function ($attribute, $value, $fail) use ($usuario) {
                $existe = User::whereRaw('LOWER(email) = ?', [mb_strtolower(trim($value))])
                    ->where('id', '!=', $usuario->id)
                    ->exists();
                if ($existe) {
                    $fail('Ya existe un usuario con ese email.');
                }
            },
        ],
        'current_password' => [
            Rule::requiredIf(fn () => $usuario->debe_cambiar_password || $request->filled('password')),
            'nullable', 'current_password',
        ],
        'password' => [
            Rule::requiredIf(fn () => $usuario->debe_cambiar_password),
            'nullable', 'string', 'min:8', 'confirmed',
            'different:current_password',
        ],
        ], [
            'required'         => 'Este campo es obligatorio.',
            'max'              => 'No puede superar los :max caracteres.',
            'nombre.regex'     => 'El nombre solo puede contener letras, espacios, apóstrofes y guiones.',
            'apellido.regex'   => 'El apellido solo puede contener letras, espacios, apóstrofes y guiones.',
            'username.min'     => 'El usuario debe tener al menos :min caracteres.',
            'username.regex'   => 'El usuario solo puede contener letras, números, puntos, guiones y guiones bajos.',
            'email.email'      => 'El email no es válido.',
            'email.regex'      => 'El email no es válido.',
            'current_password.required'         => 'Debe ingresar su contraseña actual (la temporal) para cambiarla.',
            'current_password.current_password' => 'La contraseña actual no es correcta.',
            'password.required'                 => 'Debe establecer una nueva contraseña.',
            'password.min'                      => 'La nueva contraseña debe tener al menos :min caracteres.',
            'password.confirmed'                => 'La confirmación de la contraseña no coincide.',
            'password.different'                => 'La nueva contraseña debe ser distinta de la actual.',
        ]);

        $cambioPassword = !empty($data['password']);

        $usuario->name = trim($data['nombre']);
        $usuario->apellido = trim($data['apellido']);
        $usuario->username = trim($data['username']);
        $usuario->email = mb_strtolower(trim($data['email']));


        if ($cambioPassword) {
            $usuario->password = $data['password']; 
            $usuario->debe_cambiar_password = false;
        }

        $usuario->save();

        if ($cambioPassword) {
            Auth::logoutOtherDevices($data['password']);
            $request->session()->regenerate();
        }
        return redirect() -> route('usuario.perfil') -> with('success', 'Perfil actualizado correctamente.');

    }

    public function delete(User $usuario) {
         abort_unless(auth()->user()->puedeGestionarA($usuario), 403);

        $usuario->activo = false;
        $usuario->save();
        $usuario->tokens()->delete();
        return redirect()->back()->with('success', 'Usuario desactivado correctamente.');
    }

    public function activar(User $usuario)
    {
        abort_unless(auth()->user()->puedeGestionarA($usuario), 403);

        $usuario->activo = true;
        $usuario->save();
        return redirect()->back()->with('success', 'Usuario reactivado correctamente.');
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
