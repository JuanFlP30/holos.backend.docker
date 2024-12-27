<?php namespace App\Http\Traits;

use App\Models\Notification;

/**
 * Notificaciones de base de datos
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 */
trait HasDatabaseNotifications
{
    /**
     * Get the entity's notifications.
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->with('user:id,name,paternal,maternal,profile_photo_path')->latest();
    }

    /**
     * Get the entity's read notifications.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function readNotifications()
    {
        return $this->notifications()->read();
    }

    /**
     * Get the entity's unread notifications.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }
}
