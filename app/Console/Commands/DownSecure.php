<?php namespace App\Console\Commands;
/**
 * @copyright (c) 2025 Notsoweb (https://notsoweb.com) - All rights reserved.
 */

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

/**
 * Mantenimiento seguro
 * 
 * De forma automática pondrá al sistema en modo mantenimiento con una URL secreta para
 * poder acceder al sitio. El tiempo se puede configurar desde las variables de entorno.
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class DownSecure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'down:secure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Modo mantenimiento con un hash seguro';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $secret = Uuid::uuid4();

        Artisan::call('down', [
            '--secret' => $secret
        ]);

        echo url($secret);
        echo "\n";

        Log::channel('notsoweb')->info("Maintenance Mode Secure. Key: {$secret}");

        return Command::SUCCESS;
    }
}