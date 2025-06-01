<?php namespace Database\Seeders;
/**
 * @copyright (c) 2025 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Enums\SettingTypeEk;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Configuraciones del sistema
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class SettingSeeder extends Seeder
{
    /**
     * Ejecutar sembrado de base de datos
     */
    public function run(): void
    {
        Setting::value('app.logo', url("images/logo.png"), 'Logo de la aplicación', SettingTypeEk::STRING);
        Setting::value('app.favicon', url("images/favicon.ico"), 'Favicon de la aplicación', SettingTypeEk::STRING);
    }
}