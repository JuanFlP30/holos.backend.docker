<?php namespace App\Providers;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Models\Role;
use App\Models\User;
use App\Observers\RoleObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

/**
 * Observadores de la aplicación
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class ObserverProvider extends ServiceProvider
{
    /**
     * Registrar servicios
     */
    public function register(): void
    {
        //
    }

    /**
     * Inicializar servicios
     */
    public function boot(): void
    {
        User::observe([UserObserver::class]);
        Role::observe([RoleObserver::class]);
    }
}
