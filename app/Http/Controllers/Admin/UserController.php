<?php namespace App\Http\Controllers\Admin;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\PasswordUpdateRequest;
use App\Http\Requests\Users\UserStoreRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Models\User;
use App\Supports\QuerySupport;
use Illuminate\Http\Request;
use Notsoweb\ApiResponse\Enums\ApiResponse;

/**
 * Controlador de usuarios
 * 
 * Permite la administración de los usuarios en general.
 * 
 * @author Moisés Cortés C <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class UserController extends Controller
{
    /**
     * Listar
     */
    public function index()
    {
        $users = User::orderBy('name');

        QuerySupport::queryByKeys($users, ['name', 'email']);

        return ApiResponse::OK->response([
            'models' => $users->paginate(config('app.pagination'))
        ]);
    }

    /**
     * Almacenar
     */
    public function store(UserStoreRequest $request)
    {
        $user = User::create($request->all());

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return ApiResponse::OK->response();
    }

    /**
     * Mostrar
     */
    public function show(User $user)
    {
        return ApiResponse::OK->response([
            'user' => $user
        ]);
    }

    /**
     * Actualizar
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update($request->all());

        return ApiResponse::OK->response();
    }

    /**
     * Eliminar
     */
    public function destroy(User $user)
    {
        $user->delete();

        return ApiResponse::OK->response();
    }


    /**
     * Permisos del usuario
     */
    public function permissions(User $user)
    {
        return ApiResponse::OK->response([
            'permissions' => $user->getAllPermissions()
        ]);
    }

    /**
     * Roles del usuario
     */
    public function roles(User $user)
    {
        return ApiResponse::OK->response([
            'roles' => $user
                ->roles()
                ->select('id', 'name', 'description')
                ->get()
        ]);
    }

    /**
     * Actualizar roles
     */
    public function updateRoles(Request $request, User $user)
    {
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return ApiResponse::OK->response();
    }

    /**
     * Actualizar contraseña
     */
    public function updatePassword(PasswordUpdateRequest $request, User $user)
    {
        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return ApiResponse::OK->response();
    }
}
