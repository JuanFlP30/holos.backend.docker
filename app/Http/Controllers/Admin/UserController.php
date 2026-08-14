<?php namespace App\Http\Controllers\Admin;
/**
 * @copyright (c) 2026 MCortesDev (https://mcortes.dev) - All Rights Reserved
 */

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\PasswordUpdateRequest;
use App\Http\Requests\Users\UserStoreRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Models\User;
use App\Supports\QuerySupport;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Notsoweb\ApiResponse\Enums\ApiResponse;

/**
 * Controlador de usuarios
 * 
 * Permite la administración de los usuarios en general.
 * 
 * @author Moisés Cortés C <soy@mcortes.dev>
 * 
 * @version 1.0.0
 */
class UserController extends Controller implements HasMiddleware
{
    /**
     * Middlewares
     */
    public static function middleware(): array
    {
        return [
            self::can('users.index', ['index', 'show']),
            self::can('users.destroy', ['destroy']),
            self::can('users.permissions-index', ['permissions']),
            self::can('users.permissions-update', ['updatePermissions']),
            self::can('users.roles-index', ['roles']),
            self::can('users.roles-update', ['updateRoles']),
        ];
    }

    /**
     * Listar
     */
    public function index()
    {
        $users = User::orderBy('name');

        if(!auth()->user()->isDeveloper()) {
            $users->whereNot('id', 1);
        }

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
        $user = User::create($request->validated());

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
        $user->update($request->validated());

        return ApiResponse::OK->response();
    }

    /**
     * Eliminar
     */
    public function destroy(User $user)
    {
        // Evitar eliminar usuarios primarios
        if($user->isPrimary()) {
            return ApiResponse::UNPROCESSABLE_CONTENT->response([
                'message' => 'No tienes permisos para eliminar este usuario'
            ]);
        }

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
