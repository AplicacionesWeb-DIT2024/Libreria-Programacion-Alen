<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !$user->activo || !in_array($user->role->value, $roles, true)) {
            abort(403, 'No se poseen los permisos necesarios para acceder a esta sección.');
        }

        return $next($request);
    }
}
