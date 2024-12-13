<?php namespace App\Http\Controllers;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Http\Requests\User\UserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Notsoweb\ApiResponse\Enums\ApiResponse;
use Notsoweb\LaravelCore\Supports\NotifySupport;

/**
 * Usuario autenticado
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class MyUserController extends Controller
{
    /**
     * Obtener usuario autenticado
     */
    public function show()
    {
        return ApiResponse::OK->response([
            'user' => auth()->user()
        ]);
    }

    /**
     * Actualizar
     */
    public function update(UserUpdateRequest $request)
    {
        $form = $request->validated();

        if (isset($form['photo'])) {
            auth()->user()->updateProfilePhoto($form['photo']);
        }

        auth()->user()->update($form);

        return ApiResponse::OK->response();
    }

    /**
     * Eliminar
     */
    public function destroy()
    {
        auth()->user()->delete();

        return ApiResponse::OK->response();
    }

    /**
     * Confirmar contraseña
     * 
     * Autoriza una acción si la contraseña es correcta.
     */
    public function confirmPassword(Request $request)
    {
        $form = $request->validate([
            'password' => ['required', 'string', 'min:8']
        ]);

        if (!Hash::check($form['password'], auth()->user()->password)) {
            NotifySupport::errorIn('password', __('validation.password'));
        }

        return ApiResponse::OK->response();
    }

    /**
     * Actualizar contraseña
     */
    public function updatePassword(Request $request)
    {
        $form = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if (!Hash::check($form['current_password'], auth()->user()->password)) {
            NotifySupport::errorIn('current_password', __('validation.password'));
        }

        auth()->user()->update([
            'password' => bcrypt($form['password'])
        ]);

        return ApiResponse::OK->response();
    }

    /**
     * Eliminar foto de perfil
     */
    public function destroyPhoto()
    {
        auth()->user()->deleteProfilePhoto();

        return ApiResponse::OK->response();
    }

    /**
     * Permisos
     */
    public function permissions()
    {
        return ApiResponse::OK->response([
            'permissions' => auth()->user()->getAllPermissions()
        ]);
    }

    /**
     * Roles
     */
    public function roles()
    {
        return ApiResponse::OK->response([
            'roles' => auth()->user()
                ->roles()
                ->select('id', 'name', 'description')
                ->get()
        ]);
    }
}
