<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckSolicitudMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->solicitud === 0) {
            return redirect()->route('invitado.index');
        }

        return $next($request);
    }
}
