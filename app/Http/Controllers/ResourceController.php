<?php namespace App\Http\Controllers;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Models\Setting;
use Illuminate\Http\Request;
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
     * Obtener cualquier recurso
     * 
     * Los recursos son traits que deben ser importados en este controlador. Para consumir el recurso del trait
     * se debe de enviar el nombre de la función que se desea consumir, si esta requiere parámetros, se deben de enviar,
     * sino se debe colocar un null.
     */
    public function get(Request $request)
    {
        $resources = $request->all();
        $response = [];

        foreach ($resources as $resource => $data) {
            if (method_exists($this, $resource)) {
                $response[$resource] = $this->{$resource}($data);
            }
        }

        return ApiResponse::OK->onSuccess($response);
    }

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