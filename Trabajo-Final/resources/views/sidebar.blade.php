
<div class = "d-flex flex-column">
  <div class="d-flex flex-column align-items-start flex-shrink-0 p-3 text-bg-dark" style=" position: fixed;width: 250px; height: 120vh;">
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
          <i class="fa-solid fa-house"></i>
          Home
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white">
          <i class="fa-solid fa-book"></i>
          Libros
        </a>
        <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
          <li><a href="{{route('libros.create')}}" class="nav-link text-white"> 
            <i class="fa-solid fa-arrow-right"></i>  
            Agregar libro
          </a>
          </li>
          <li><a href="{{route('libros.index')}}" class="nav-link text-white"> 
            <i class="fa-solid fa-arrow-right"></i>
            Listar libros
          </a>
        </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white">
          <i class="fa-solid fa-building"></i>
          Editoriales
        </a>
        <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
          <li><a href="{{route('editoriales.create')}}" class="nav-link text-white">  
            <i class="fa-solid fa-arrow-right"></i>
            Agregar editorial
          </a>
        </li>
          <li><a href="{{route('editoriales.index')}}" class="nav-link text-white"> 
            <i class="fa-solid fa-arrow-right"></i>
            Listar editoriales
          </a>
        </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white">
          <i class="fa-solid fa-list"></i>
          Categorias
        </a>
        <ul class="nav nav-pills flex-column mb-auto" style="display: none;">
          <li>
            <a href=" {{route('categorias.create')}}" class="nav-link text-white">  
            <i class="fa-solid fa-arrow-right"></i>
            Agregar categoria
            </a>
          </li>
          <li>
            <a href=" {{ route('categorias.index') }}" class="nav-link text-white">  
            <i class="fa-solid fa-arrow-right"></i>
            Listar categorias
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white">
          <i class="fa-solid fa-list"></i>
          Subcategorias
        </a>
        <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
          <li>
            <a href=" {{route('subcategorias.create')}}" class="nav-link text-white"> 
              <i class="fa-solid fa-arrow-right"></i>
               Agregar subcategoria
            </a>
          </li>
          <li>
            <a href=" {{route('subcategorias.index')}}" class="nav-link text-white"> 
              <i class="fa-solid fa-arrow-right"></i>
              Listar subcategorias
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white">
          <i class="fa-solid fa-globe"></i>
          Paises
        </a>
        <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
          <li>
            <a href="{{route('paises.create')}}" class="nav-link text-white"> 
              <i class="fa-solid fa-arrow-right"></i>
              Agregar pais
            </a>
          </li>
          <li>
            <a href="{{route('paises.index')}}" class="nav-link text-white">
              <i class="fa-solid fa-arrow-right"></i>
              Listar paises
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white">
          <i class="fa-solid fa-person"></i>
          Autores
        </a>
        <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
          <li>
            <a href="{{route('autores.create')}}" class="nav-link text-white"> 
              <i class="fa-solid fa-arrow-right"></i>
              Agregar autor
            </a>
          </li>
          <li>
            <a href="{{route('autores.index')}}" class="nav-link text-white"> 
              <i class="fa-solid fa-arrow-right"></i>
              Listar autores
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white">
          <i class="fa-solid fa-piggy-bank"></i>
          Bancos
        </a>
        <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
          <li>
            <a href="{{route('bancos.create')}}" class="nav-link text-white"> 
              <i class="fa-solid fa-arrow-right"></i>
              Agregar banco
            </a>
          </li>
          <li>
            <a href="{{route('bancos.index')}}" class="nav-link text-white">
              <i class="fa-solid fa-arrow-right"></i>
              Listar bancos
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white">
          <i class="fa-brands fa-cc-mastercard"></i>
          Entidades Crediticias
        </a>
        <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
          <li>
            <a href="{{ route('entidades.create') }}" class="nav-link text-white"> 
              <i class="fa-solid fa-arrow-right"></i>
              Agregar entidad
            </a>
          </li>
          <li>
            <a href="{{ route('entidades.index') }}" class="nav-link text-white"> 
              <i class="fa-solid fa-arrow-right"></i>
              Listar entidades
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white">
          <i class="fa-solid fa-user"></i>
          Usuarios
        </a>
        <ul class= "nav nav-pills flex-column mb-auto" style="display: none;">
          <li>
            <a href="{{ route('usuarios.create') }}" class="nav-link text-white"> 
              <i class="fa-solid fa-arrow-right"></i>
              Agregar usuario
            </a>
          </li>
          <li>
            <a href="{{ route('usuarios.index') }}" class="nav-link text-white">
              <i class="fa-solid fa-arrow-right"></i>
              Listar usuarios
            </a>
          </li>
        </ul>
      </li>
      <div class="d-flex" >
        <ul class = "nav nav-pills flex-column mb-auto"> 
          <li class="nav-item">
            <a href="{{ route('logout')}}" class="nav-link text-white" style= "position:absolute; bottom:10em;">
              <i class="fa-solid fa-power-off"></i>
              Cerrar Sesion
            </a>
          </li>
        </ul>
      </div>
    </ul>
    
    <script src="{{asset('js/sidebar.js') }} "></script>
  </div>
</div>
