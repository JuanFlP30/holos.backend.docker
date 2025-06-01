<?php namespace App\Http\Controllers;
/**
 * @copyright (c) 2025 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use Notsoweb\ApiResponse\Enums\ApiResponse;

/**
 * Controlador del servidor
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class ServerController extends Controller
{
    /**
     * Estado del servidor
     */
    public function status()
    {
        return ApiResponse::OK->response([
            "status" => "ok"
        ]);
    }

    /**
     * Versión
     */
    public function version()
    {
        return ApiResponse::OK->response([
            "version" => config('app.version')
        ]);
    }

    /**
     * Login requerido
     */
    public function loginRequired()
    {
        return ApiResponse::OK->response([
            "message" => __('login.required')
        ]);
    }
}