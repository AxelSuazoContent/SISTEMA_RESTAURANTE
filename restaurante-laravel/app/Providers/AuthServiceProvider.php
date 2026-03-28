<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate para administradores
        Gate::define('admin', function ($user) {
            return $user->rol === 'admin';
        });

        // Gate para recepcionistas
        Gate::define('recepcionista', function ($user) {
            return in_array($user->rol, ['admin', 'recepcionista']);
        });

        // Gate para cocina
        Gate::define('cocina', function ($user) {
            return in_array($user->rol, ['admin', 'cocina']);
        });
    }
}
