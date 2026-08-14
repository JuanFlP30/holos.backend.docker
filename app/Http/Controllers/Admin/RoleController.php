<?php namespace App\Http\Controllers\Admin;
/**
 * @copyright (c) 2026 MCortesDev (https://mcortes.dev) - All Rights Reserved
 */

use App\Events\UpdateRoleUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\RoleStoreRequest;
use App\Http\Requests\Roles\RoleUpdateRequest;
use App\Models\Role;
use App\Supports\QuerySupport;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Notsoweb\ApiResponse\Enums\ApiResponse;

/**
 * Roles del sistema
 * 
 * @author Moisés Cortés C. <soy@mcortes.dev>
 * 
 * @version 1.0.0
 */
class RoleController extends Controller implements HasMiddleware
{
    /**
     * Middlewares
     */
    public static function middleware(): array
    {
        return [
            self::can('roles.index', ['index', 'show']),
            self::can('roles.destroy', ['destroy']),
            self::can('roles.permissions-index', ['permissions']),
            self::can('roles.permissions-update', ['updatePermissions']),
        ];
    }

    /**
     * Listar
     */
    public function index()
    {
        $model = Role::orderBy('description');

        if(!auth()->user()->isDeveloper()) {
            $model->whereNot('id', 1);
        }

        QuerySupport::queryByKey($model, request(), 'name');

        return ApiResponse::OK->response([
            'models' => $model
                ->paginate(config('app.pagination'))
        ]);
    }

    /**
     * Almacenar
     */
    public function store(RoleStoreRequest $request)
    {
        Role::create($request->all());

        return ApiResponse::OK->response();
    }

    /**
     * Mostrar
     */
    public function show(Role $role)
    {
        return ApiResponse::OK->response([
            'model' => $role
        ]);
    }

    /**
     * Actualizar
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        $role->update($request->all());

        return ApiResponse::OK->response();
    }

    /**
     * Eliminar
     */
    public function destroy(Role $role)
    {
        // Evitar eliminar roles primarios
        if(in_array($role->id, [1, 2])) {
            return ApiResponse::UNPROCESSABLE_CONTENT->response([
                'message' => 'No se puede eliminar el rol primario'
            ]);
        }

        $role->delete();

        return ApiResponse::OK->response();
    }

    /**
     * Permisos
     */
    public function permissions(Role $role)
    {
        return ApiResponse::OK->response([
            'permissions' => $role->permissions
        ]);
    }

    /**
     * Actualizar permisos
     */
    public function updatePermissions(Role $role, Request $request)
    {
        $role->syncPermissions($request->get('permissions', []));

        UpdateRoleUser::dispatch($role);

        return ApiResponse::OK->response();
    }
}
