<?php namespace App\Console\Commands;
/**
 * @copyright (c) 2026 MCortesDev (https://mcortes.dev) - All rights reserved.
 */

use Illuminate\Console\Command;

/**
 * Servicio de broadcast
 * 
 * Permite iniciar, detener y reiniciar el servicio de broadcast para el envio
 * de notifiaciones en tiempo real.
 * 
 * @author Moisés Cortés C. <soy@mcortes.dev>
 * 
 * @version 1.0.0
 */
class Broadcast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:broadcast
        {--start : Iniciar servicio de broadcast} 
        {--stop : Detener servicio de broadcast} 
        {--restart : Reiniciar servicio de broadcast}
        {--status : Mostrar estado del servicio de broadcast}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Servicio de broadcast';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appName = config('app.slug') . '-reverb';

        if ($this->option('stop') || $this->option('restart')) {
            echo "# Deteniendo servicio de reverb... \n";
            echo shell_exec("pm2 delete {$appName}");
        }

        if ($this->option('start') || $this->option('restart')) {
            echo "# Iniciando servicio de reverb... \n";
            echo shell_exec("pm2 start \"php artisan reverb:start\" --name {$appName}");
            echo shell_exec("pm2 save");
        }

        if ($this->option('status')) {
            echo "# Estado del servicio de reverb: \n";
            echo shell_exec("pm2 status {$appName}");
        }
    }
}
