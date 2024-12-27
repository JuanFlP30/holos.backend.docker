<?php namespace App\Providers;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

/**
 * Proveedor de servicios de la aplicación
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registrar cualquier servicio de la aplicación
     */
    public function register(): void
    {
        if ($this->app->environment('local') && config('telescope.enabled') == 'true') {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::loadKeysFrom(storage_path('app/keys'));
        Passport::tokensExpireIn(now()->addDays(30));
        Passport::refreshTokensExpireIn(now()->addDays(60));
        Passport::personalAccessTokensExpireIn(now()->addMonths(12));

        // Acceso a Pulse
        Gate::define('viewPulse', function (User $user) {
            return $user->hasRole('developer');
        });
    }
}
