<?php

namespace App\Providers;

use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class NotificationsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        View::composer('*', function ($view) {
            $usuarioAutenticado = Auth::user();
            if ($usuarioAutenticado) {

                $notificaciones = Notificacion::where('usuario_id', $usuarioAutenticado->id)
                    ->latest()
                    ->take(5)
                    ->get();

                $view->with('notificaciones', $notificaciones);
            }
        });
    }
}
