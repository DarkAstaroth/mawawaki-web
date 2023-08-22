<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckVerificacionMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->verificada === 0) {
            return redirect()->route('verificar.cuenta');
        }

        return $next($request);
    }
}
