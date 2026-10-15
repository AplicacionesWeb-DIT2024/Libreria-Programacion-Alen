<?php

namespace App\Http\Controllers;
use App\Enums\Role;
use App\Models\User;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index() 
    {
        $clientes = User::where('role', Role::Cliente)
            ->orderBy('id')
            ->get();

        return view('listarClientes', compact('clientes'));
    }

}
