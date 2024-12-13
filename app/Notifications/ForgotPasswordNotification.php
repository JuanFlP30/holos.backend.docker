<?php namespace App\Notifications;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Descripción
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class ForgotPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Obtener los canales de entrega de la notificación
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Obtener la representación del mensaje de la notificación
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('auth.forgot-password.subject'))
            ->markdown('user.password-forgot');
    }

    /**
     * Obtener la representación en array de la notificación
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
