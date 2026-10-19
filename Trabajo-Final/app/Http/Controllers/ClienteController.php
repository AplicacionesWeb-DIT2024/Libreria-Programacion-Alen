<?php

namespace App\Http\Controllers;
use App\Enums\Role;
use App\Models\User;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index() 
    {
        $clientes = User::with(['cliente','usuarioCreacion','usuarioModificacion'])
            ->where('role', Role::Cliente)
            ->orderBy('id')
            ->get();

        return view('listarClientes', compact('clientes'));
    }

}
