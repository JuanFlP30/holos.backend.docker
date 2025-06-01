<?php namespace App\Http\Controllers\Admin;
/**
 * @copyright (c) 2025 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Http\Controllers\Controller;
use App\Models\PermissionType;
use Notsoweb\ApiResponse\Enums\ApiResponse;

/**
 * Tipos de permisos
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class PermissionTypeController extends Controller
{
    /**
     * Listar todo
     */
    public function all()
    {
        return ApiResponse::OK->response([
            'models' => PermissionType::orderBy('name')->get()
        ]);
    }

    /**
     * Listar todo con permisos
     */
    public function allWithPermissions()
    {
        return ApiResponse::OK->response([
            'models' => PermissionType::with('permissions')->orderBy('name')->get()
        ]);
    }
}
