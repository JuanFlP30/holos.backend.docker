<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Iniciar servicio de broadcast
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
        if ($this->option('stop') || $this->option('restart')) {
            echo "# Deteniendo servicio de broadcast... \n";
            echo shell_exec('pm2 delete broadcast');
        }

        if ($this->option('start') || $this->option('restart')) {
            echo "# Iniciando servicio de broadcast... \n";
            echo shell_exec("pm2 start  --name broadcast \"php artisan reverb:start --hostname=" . env('APP_URL') . "\"");
        }

        if ($this->option('status')) {
            echo "# Estado del servicio de broadcast: \n";
            echo shell_exec('pm2 status broadcast');
        }
    }
}
