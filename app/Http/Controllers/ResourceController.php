<?php namespace App\Http\Controllers;
/**
 * @copyright (c) 2025 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Models\Setting;
use Notsoweb\ApiResponse\Enums\ApiResponse;
use Tighten\Ziggy\Ziggy;

/**
 * Recursos de la aplicación
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class ResourceController extends Controller
{
    /**
     * Información de la aplicación
     */
    public function app()
    {
        return ApiResponse::OK->axios([
            'logo' => Setting::value('app.logo'),
            'favicon' => Setting::value('app.favicon'),
            'version' => config('app.version'),
        ]);
    }

    /**
     * Rutas de la aplicación
     */
    public function routes()
    {
        return ApiResponse::OK->axios((new Ziggy('api'))->toArray());
    }
}