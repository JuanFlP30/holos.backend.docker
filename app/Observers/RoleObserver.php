<?php namespace App\Observers;
/**
 * @copyright (c) 2025 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Models\Role;
use App\Models\UserEvent;

/**
 * Observador del modelo Role
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class RoleObserver
{
    /**
     * Manipulador del evento "created" del modelo Role
     */
    public function created(Role $role): void
    {
        UserEvent::report(
            model: $role,
            event: __FUNCTION__
        );
    }

    /**
     * Manipulador del evento "updated" del modelo Role
     */
    public function updated(Role $role): void
    {
        UserEvent::report(
            model: $role,
            event: __FUNCTION__,
            reportChanges: true
        );
    }

    /**
     * Manipulador del evento "deleted" del modelo Role
     */
    public function deleted(Role $role): void
    {
        UserEvent::report(
            model: $role,
            event: __FUNCTION__,
        );
    }

    /**
     * Manipulador del evento "restored" del modelo Role
     */
    public function restored(Role $role): void
    {
        UserEvent::report(
            model: $role,
            event: __FUNCTION__,
        );
    }

    /**
     * Manipulador del evento "force deleted" del modelo Role
     */
    public function forceDeleted(Role $role): void
    {
        UserEvent::report(
            model: $role,
            event: __FUNCTION__,
        );
    }
}
