<?php namespace App\Observers;
/**
 * @copyright (c) 2025 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Models\User;
use App\Models\UserEvent;

/**
 * Observador del modelo User
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class UserObserver
{
    /**
     * Manipulador del evento "created" del modelo User
     */
    public function created(User $user): void
    {
        UserEvent::report(
            model: $user,
            event: __FUNCTION__,
            key: 'email'
        );
    }

    /**
     * Manipulador del evento "updated" del modelo User
     */
    public function updated(User $user): void
    {
        UserEvent::report(
            model: $user,
            event: __FUNCTION__,
            key: 'email',
            reportChanges: true
        );
    }

    /**
     * Manipulador del evento "deleted" del modelo User
     */
    public function deleted(User $user): void
    {
        UserEvent::report(
            model: $user,
            event: __FUNCTION__,
            key: 'email'
        );
    }

    /**
     * Manipulador del evento "restored" del modelo User
     */
    public function restored(User $user): void
    {
        UserEvent::report(
            model: $user,
            event: __FUNCTION__,
            key: 'email'
        );
    }

    /**
     * Manipulador del evento "force deleted" del modelo User
     */
    public function forceDeleted(User $user): void
    {
        UserEvent::report(
            model: $user,
            event: __FUNCTION__,
            key: 'email'
        );
    }
}
