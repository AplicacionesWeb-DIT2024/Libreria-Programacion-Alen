<div class="offcanvas-md offcanvas-start text-bg-dark min-vh-100" tabindex="-1" id="sidebar" style="width: 250px;">

  <div class="offcanvas-header">
    <button type="button" class="btn-close btn-close-white d-md-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body d-flex flex-column p-3 min-vh-100">
     <a href="{{ route('home') }}" class="d-flex align-items-center text-white text-decoration-none">
      <img src="{{ asset('images/logobyte.png') }}" class="img-fluid me-2" alt="ByteBooks" width="70" height="60">
      <span class="fs-4">ByteBooks</span>
    </a>
    <hr class="d-none d-md-block mt-0">
    @auth
    <button class="btn d-flex align-items-center text-white mb-1 w-100 border-0 bg-transparent p-0 text-start"
            type="button" data-bs-toggle="collapse" data-bs-target="#userMenu"
            aria-expanded="false" aria-controls="userMenu">
      <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2 text-white fw-bold"
          style="width: 45px; height: 45px; flex-shrink: 0;">
        {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
      </div>
      <div class="text-truncate flex-grow-1">
        <span class="fw-semibold">{{ auth()->user()->username }}</span>
      </div>
      <i class="fa-solid fa-chevron-down small ms-2"></i>
    </button>

    <div class="collapse mb-3" id="userMenu">
      <ul class="nav nav-pills flex-column">
        <li class="nav-item">
          <a href="{{ route('usuario.perfil') }}" class="nav-link text-white d-flex align-items-center gap-2">
            <i class="fa-solid fa-user fa-fw"></i>
            Ver perfil
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('logout') }}" class="nav-link text-white d-flex align-items-center gap-2">
            <i class="fa-solid fa-arrow-right-from-bracket fa-fw"></i>
            Cerrar Sesion
          </a>
        </li>
      </ul>
    </div>
  @endauth

    <ul class="nav nav-pills flex-column">
      <li class="nav-item">
        <a href="{{ route('home') }}" class="nav-link text-white" aria-current="page">
          <i class="fa-solid fa-house"></i>
          Home
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('libros.index') }}" class="nav-link text-white">
          <i class="fa-solid fa-book"></i>
          Libros
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('editoriales.index') }}" class="nav-link text-white">
          <i class="fa-solid fa-building"></i>
          Editoriales
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('categorias.index') }}" class="nav-link text-white submenu-toggle">
          <i class="fa-solid fa-list"></i>
          Categorias
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('subcategorias.index') }}" class="nav-link text-white submenu-toggle">
          <i class="fa-solid fa-list"></i>
          Subcategorias
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('autores.index') }}" class="nav-link text-white submenu-toggle">
          <i class="fa-solid fa-person"></i>
          Autores
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('usuarios.index') }}" class="nav-link text-white">
          <i class="fa-solid fa-users"></i>
          Usuarios
        </a>
      </li>

      <li class="nav-item">
        <a href="#" class="nav-link text-white">
          <i class="fa-solid fa-chart-column"></i>
          Reportes
        </a>
      </li>
    </ul>

  </div>
</div>

<script src="{{ asset('js/sidebar.js') }}"></script>