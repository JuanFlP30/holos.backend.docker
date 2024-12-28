<?php namespace App\Events;
/**
 * @copyright (c) 2024 Notsoweb (https://notsoweb.com) - All rights reserved.
 */

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Notificación global
 * 
 * Notificación enviada a todos los usuarios conectados al canal Global.
 * 
 * @author Moisés de Jesús Cortés Castellanos <ing.moisesdejesuscortesc@notsoweb.com>
 * 
 * @version 1.0.0
 */
class GlobalNotification implements ShouldBroadcast
{
    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    /**
     * Constructor
     */
    public function __construct(
        public string $title,
        public string $message,
        public string $type = 'info',
        public int $timeout = 15
    ) {}

    /**
     * Nombre del evento
     */
    public function broadcastAs(): string
    {
        return 'App\Events\Notification';
    }

    /**
     * Canal de envío
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('Global');
    }
}
