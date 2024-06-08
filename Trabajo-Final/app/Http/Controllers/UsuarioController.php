<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index() {
        $usuarios = User::orderBy('username', 'asc') -> paginate(3);
        return view('listarUsuarios', compact('usuarios'));
    }

    public function store(Request $request) {

        $usuario = new User(); 
        $usuario->name = $request->nombre; 
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        $usuario->domicilio = $request->domicilio;
        $usuario->password = $request->password;
        $usuario->admin = True;
        $usuario->save();

        return redirect() ->back() -> with('success', 'Usuario creado exitosamente');
    }


    public function create() {
        return view('agregarUsuario');
    }


    public function edit(User $usuario) {
        return view('modificarUsuario', compact('usuario') );
    }

    public function update(Request $request, User $usuario) {
        $usuario->name = $request->nombre; 
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        $usuario->domicilio = $request->domicilio;
        $usuario->password = $request->password;

        $usuario->save();
        return redirect() -> route('usuarios.index');

    }

    public function delete(User $usuario) {
        $usuario->delete();
        return redirect() -> route('usuarios.index');
    }

}
