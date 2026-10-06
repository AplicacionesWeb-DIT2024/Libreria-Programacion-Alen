<form action = "{{route('usuarios.store')}}" method = "POST">
            @csrf
            <div class="form-group mt-3 mb-3">
                <label for="nombre">Nombre del administrador </label>
                <input type="text" class="form-control" required = "" id="nombre" name = "nombre">
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="apellido">Apellido del administrador </label>
                <input type="text" class="form-control" required = "" id="apellido" name = "apellido">
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="email">Email del administrador </label>
                <input type="text" class="form-control" required = "" id="email" name = "email">
            </div>
             <div class="form-group mt-3 mb-3">
                <label for="username">Usuario del administrador </label>
                <input type="text" class="form-control" required = "" id="username" name = "username">
            </div>
            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-primary btn-block btn-login align-items-center"> Agregar usuario</button>
            </div>
</form>