<?php namespace App\Console\Commands;
/**
 * @copyright Copyright (c) 2023 Notsoweb (https://notsoweb.com) - All rights reserved.
 */

use App\Events\GlobalNotification;
use Illuminate\Console\Command;

/**
 * Notificación global
 * 
 * Notificación global a todos los usuarios conectados.
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class NotificationGlobal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:global
        {--message=Notificación de prueba : Mensaje de la notificación}
        {--title=Notificación Global : Título de la notificación}
        {--type=info : Tipo de notificación (info, success, warning, error)}
        {--timeout=15 : Tiempo de duración de la notificación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar notificación a todos los usuarios conectados';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $message = $this->option('message');
        $title = $this->option('title');
        $type = $this->option('type');
        $timeout = $this->option('timeout');

        broadcast(new GlobalNotification(
            $title,
            $message,
            $type,
            $timeout
        ));
        
        return Command::SUCCESS;
    }
}