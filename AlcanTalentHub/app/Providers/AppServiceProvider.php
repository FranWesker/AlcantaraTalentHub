<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * definimos las políticas de acceso para funciones específicas de empresa y estudiante, asegurando que solo los usuarios con los roles adecuados puedan acceder a ciertas características de la aplicación.
     */
    public function boot(): void
    {
        // Definimos el acceso para funciones de empresa (Empresas y Admins)
        Gate::define('manage-company-features', function (User $user) {
            return $user->isCompany() || $user->isAdmin();
        });

        // Definimos el acceso para funciones de estudiante (Estudiantes y Admins)
        Gate::define('manage-student-features', function (User $user) {
            return $user->isStudent() || $user->isAdmin();
        });
    }
}
