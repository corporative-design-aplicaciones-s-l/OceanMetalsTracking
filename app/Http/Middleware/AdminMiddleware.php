<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Redirigir o mostrar un mensaje si no es administrador
        return redirect('/')->with('error', 'No tienes permiso para acceder a esta sección.');
    }
}