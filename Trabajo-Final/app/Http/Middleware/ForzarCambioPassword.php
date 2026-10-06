<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForzarCambioPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->debe_cambiar_password && !$request->routeIs('usuario.perfil', 'usuarios.update', 'logout')) {
            return redirect()->route('usuario.perfil')
                ->with('aviso', 'Debe cambiar su contraseña temporal para continuar');
        }
        return $next($request);
    }
}
