<?php namespace App\Http\Traits;
/**
 * @copyright (c) 2025 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use Illuminate\Notifications\RoutesNotifications;

/**
 * Notificaciones personalizadas
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
trait IsNotifiable
{
    use HasDatabaseNotifications, RoutesNotifications;
}
