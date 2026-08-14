<?php namespace App\Http\Traits;
/**
 * @copyright (c) 2026 MCortesDev (https://mcortes.dev) - All Rights Reserved
 */

use Illuminate\Notifications\RoutesNotifications;

/**
 * Notificaciones personalizadas
 * 
 * @author Moisés Cortés C. <soy@mcortes.dev>
 * 
 * @version 1.0.0
 */
trait IsNotifiable
{
    use HasDatabaseNotifications, RoutesNotifications;
}
