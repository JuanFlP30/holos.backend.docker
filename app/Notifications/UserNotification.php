<?php namespace App\Notifications;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * Notificación de usuario
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class UserNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $title,
        public string $description,
        public ?string $message = null,
        public string $type = 'info',
        public int $timeout = 20,
        public bool $save = true,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $vias = ['broadcast'];

        if ($this->save) {
            $vias[] = 'database';
        }

        return $vias;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'message' => $this->message,
            'type' => $this->type,
        ];
    }

    /**
     * Transmitir notificación
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => $this->title,
            'description' => $this->description,
            'typeNotification' => $this->type,
            'timeout' => $this->timeout,
        ]);
    }
}
