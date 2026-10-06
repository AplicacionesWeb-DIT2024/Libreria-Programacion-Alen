@extends('main')


@section('title', 'Home')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection
@section('body')
    <div class= "container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold mb-0">Listado de opciones</h1>
        </div>
        

        <div class="row g-4">
            <div class="col-md-3">
                <div onclick="location.href='{{ route('libros.index') }}'" 
                    class="card dashboard-card text-white text-center bg-libros p-4 h-100">
                    <div class="dashboard-icon"><i class="fa-solid fa-book"></i></div>
                    <h5 class="fw-bold">Libros</h5>
                    <p class="small opacity-75">Gestionar los libros del sistema</p>
                </div>
            </div>

            <div class="col-md-3">
                <div onclick="location.href='{{ route('autores.index') }}'"
                    class="card dashboard-card text-white text-center bg-autores p-4 h-100">
                    <div class="dashboard-icon"><i class="fa-solid fa-pen"></i></div>
                    <h5 class="fw-bold">Autores</h5>
                    <p class="small opacity-75">Administrar autores de libros</p>
                </div>
            </div>

            <div class="col-md-3">
                <div onclick="location.href='{{ route('editoriales.index') }}'"
                    class="card dashboard-card text-white text-center bg-editoriales p-4 h-100">
                    <div class="dashboard-icon"><i class="fa-solid fa-building"></i></div>
                    <h5 class="fw-bold">Editoriales</h5>
                    <p class="small opacity-75">Gestionar editoriales registradas</p>
                </div>
            </div>

            <div class="col-md-3">
                <div onclick="location.href='{{ route('categorias.index') }}'"
                    class="card dashboard-card text-white text-center bg-categorias p-4 h-100">
                    <div class="dashboard-icon"><i class="fa-solid fa-tag"></i></div>
                    <h5 class="fw-bold">Categorías</h5>
                    <p class="small opacity-75">Ver y administrar categorías</p>
                </div>
            </div>

            <div class="col-md-3">
                <div onclick="location.href='{{ route('subcategorias.index') }}'"
                    class="card dashboard-card text-white text-center bg-subcategorias p-4 h-100">
                    <div class="dashboard-icon"><i class="fa-solid fa-folder-open"></i></div>
                    <h5 class="fw-bold">Subcategorías</h5>
                    <p class="small opacity-75">Ver y administrar subcategorías</p>
                </div>
            </div>

            <div class="col-md-3">
                <div onclick="location.href='{{ route('usuarios.index') }}'"
                    class="card dashboard-card text-white text-center bg-usuarios p-4 h-100">
                    <div class="dashboard-icon"><i class="fa-solid fa-users"></i></div>
                    <h5 class="fw-bold">Usuarios</h5>
                    <p class="small opacity-75">Ver y administrar usuarios</p>
                </div>
            </div>

            <div class="col-md-3">
                <div onclick="location.href='{{ route('subcategorias.index') }}'"
                    class="card dashboard-card text-white text-center bg-pedidos p-4 h-100">
                    <div class="dashboard-icon"><i class="fa-solid fa-truck"></i></div>
                    <h5 class="fw-bold">Pedidos</h5>
                    <p class="small opacity-75">Ver y administrar pedidos</p>
                </div>
            </div>

            <div class="col-md-3">
                <div onclick="location.href='{{ route('subcategorias.index') }}'"
                    class="card dashboard-card text-white text-center bg-reporte p-4 h-100">
                    <div class="dashboard-icon"><i class="fa-solid fa-chart-column"></i></div>
                    <h5 class="fw-bold">Reportes</h5>
                    <p class="small opacity-75"> Ver estadisticas y reportes</p>
                </div>
            </div>
        </div>
    </div>
@endsection

