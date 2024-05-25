

<div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 250px; height: 120vh;">
  <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
    <div>
      <img src="{{asset('images/logobyte.png') }}" class="img-fluid" alt="" width="70" height="60">
    </div>
    <span class="fs-4">ByteBooks</span>
  </a>
  <hr>
  <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
      <a href="{{route('home')}}" class="nav-link text-white" aria-current="page">
        Home
      </a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link text-white">
         Libros
      </a>
      <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
        <li><a href="#" class="nav-link text-white"> -> Agregar libro</a></li>
        <li><a href="#" class="nav-link text-white"> -> Listar libros</a></li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link text-white">
        Categorias
      </a>
      <ul class="nav nav-pills flex-column mb-auto" style="display: none;">
        <li><a href=" {{route('categorias.create')}}" class="nav-link text-white"> -> Agregar categoria</a></li>
        <li><a href=" {{ route('categorias.index') }}" class="nav-link text-white"> -> Listar categorias</a></li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link text-white">
        Subcategorias
      </a>
      <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
        <li><a href=" {{route('subcategorias.create')}}" class="nav-link text-white"> -> Agregar subcategoria</a></li>
        <li><a href=" {{route('subcategorias.index')}}" class="nav-link text-white"> -> Listar subcategorias</a></li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link text-white">
        Paises
      </a>
      <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
        <li><a href="{{route('paises.create')}}" class="nav-link text-white"> -> Agregar pais</a></li>
        <li><a href="{{route('paises.index')}}" class="nav-link text-white"> -> Listar paises</a></li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link text-white">
        Autores
      </a>
      <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
        <li><a href="#" class="nav-link text-white"> -> Agregar autor</a></li>
        <li><a href="#" class="nav-link text-white"> -> Listar autores</a></li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link text-white">
        Bancos
      </a>
      <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
        <li><a href="#" class="nav-link text-white"> -> Agregar banco</a></li>
        <li><a href="#" class="nav-link text-white"> -> Listar bancos</a></li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link text-white">
        Entidades Crediticias
      </a>
      <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
        <li><a href="#" class="nav-link text-white"> -> Agregar entidad</a></li>
        <li><a href="#" class="nav-link text-white"> -> Listar entidades</a></li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link text-white">
        Usuarios
      </a>
      <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
        <li><a href="#" class="nav-link text-white"> -> Agregar usuario</a></li>
        <li><a href="#" class="nav-link text-white"> -> Listar usuarios</a></li>
      </ul>
    </li>
  </ul>
  <script src="{{asset('js/sidebar.js') }} "></script>
</div>
