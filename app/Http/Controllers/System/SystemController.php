<?php namespace App\Http\Controllers\System;
/**
 * @copyright 2024 Notsoweb (https://notsoweb.com) - All rights reserved.
 */

use App\Http\Controllers\Controller;
use App\Models\Role;
use Notsoweb\ApiResponse\Enums\ApiResponse;
use Spatie\Permission\Models\Permission;

/**
 * Recursos del sistema
 * 
 * Contiene determinados recursos que el sistema requiere para funcionar por parte del
 * frontend.
 * 
 * @author Moisés de Jesús Cortés Castellanos <ing.moisesdejesuscortesc@notsoweb.com>
 * 
 * @version 1.0.0
 */
class SystemController extends Controller
{
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
