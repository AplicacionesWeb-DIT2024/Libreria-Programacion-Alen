<p>¡Hola!</p>

<p>Se ha creado una nueva cuenta para ti en ByteBooks</p>

<p><strong>Usuario:</strong> {{ $usuario->username }}</p>
<p><strong>Contraseña temporal:</strong> {{ $passTemporal }}</p>

<p>Por seguridad, vas a tener que cambiarla apenas inicies sesión.</p>

<p><a href="{{ route('login') }}">Ir a iniciar sesión</a></p>