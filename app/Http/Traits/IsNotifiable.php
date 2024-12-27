<?php namespace App\Http\Traits;

use Illuminate\Notifications\RoutesNotifications;

/**
 * Notificaciones personalizadas
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 */
trait IsNotifiable
{
    use HasDatabaseNotifications, RoutesNotifications;
}
