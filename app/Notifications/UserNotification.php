<?php namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

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
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [
            'broadcast',
            'database'
        ];
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
