<?php namespace App\Http\Controllers\System;
/**
 * @copyright (c) 2026 MCortesDev (https://mcortes.dev) - All rights reserved.
 */

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Notsoweb\ApiResponse\Enums\ApiResponse;
use Spatie\Permission\Models\Permission;

/**
 * Recursos del sistema
 * 
 * Contiene determinados recursos que el sistema requiere para funcionar por parte del
 * frontend.
 * 
 * @author Moisés Cortés C. <soy@mcortes.dev>
 * 
 * @version 1.0.0
 */
class SystemController extends Controller implements HasMiddleware
{
    /**
     * Middlewares
     */
    public static function middleware(): array
    {
        return [
            self::can('roles.index', ['roles', 'permissions']),
        ];
    }

    /**
     * Listar permisos del sistema
     */
    public function permissions()
    {
        return ApiResponse::OK->response([
            'permissions' => Permission::orderBy('name')
                ->select('id', 'name', 'description')
                ->get()
        ]);
    }

    /**
     * Listar roles del sistema
     */
    public function roles()
    {
        return ApiResponse::OK->response([
            'roles' => Role::orderBy('description')
                ->select('id', 'name', 'description')
                ->get()
        ]);
    }
}
