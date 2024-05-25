<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LibroController extends Controller
{
    public function index() {
        return 'Bienvenido a la libreria ByteBooks';

    }

    public function create() {
        return 'Aca podes crear los libros';
    }

    public function show($nombre) {
        return "El libro elegido es: $nombre";
    }
}
