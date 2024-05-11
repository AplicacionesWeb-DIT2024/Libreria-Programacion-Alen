@extends('main')


@section('body')
<div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 280px; height: 100vh;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <div>
        <img src="images/logobyte.png" class = "img-fluid" alt = "" width = "70" height="60">
      </div>
      <span class="fs-4">ByteBooks</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item">
        <a href="#" class="nav-link active" aria-current="page">
          Home
        </a>
      </li>
      <li class = "nav-item">
        <a href="#" class="nav-link text-white">
          Libros
          <ul class = "sub-menu" style = "display: none;">
            <li> <a href = # class = "btn-like"> Agregar libros </a> </li>
            <li> <a href = # class = "btn-like"> Listar libros </a> </li>
          </ul>
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          Categorias
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          Subcategorias
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          Paises
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          Autores
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          Bancos
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          Entidades Crediticias
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          Usuarios
        </a>
      </li>
    </ul>
    <script src = "js/sidebar.js"> </script>
  </div>

  @endsection()