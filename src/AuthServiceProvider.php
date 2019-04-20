<?php

namespace Bendt\Auth;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Bendt\Auth\Middleware\RoleCheck;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        Schema::defaultStringLength(191);

        $this->publishes([
            __DIR__.'/config/bendt-auth.php' => config_path('bendt-auth.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/Database/migrations' => database_path('migrations'),
        ], 'database');

        //Require Routes if not disabled
        if(!config('bendt-auth.routes_disabled', false)) {
            require __DIR__ . '/routes/web.php';
        }

        //Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        //Load Views
        $this->loadViewsFrom(__DIR__ . '/Views', 'bendt-auth');

        //Push route middleware
        $router->aliasMiddleware('authorize', RoleCheck::class);

        //Define Gates
        $this->registerAuthorizationPolicies();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    public function registerAuthorizationPolicies()
    {
        Gate::define('hasAnyRole', function($user, ...$roles) {
            return AuthManager::userInAnyRole($roles);
        });

        Gate::define('hasAllRoles', function($user, ...$roles) {
            return AuthManager::userInAllRoles($roles);
        });
    }
}
