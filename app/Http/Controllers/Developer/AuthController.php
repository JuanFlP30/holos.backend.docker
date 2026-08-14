<?php namespace App\Http\Controllers\Developer;
/**
 * @copyright (c) 2026 MCortesDev (https://mcortes.dev) - All Rights Reserved
 */

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Notsoweb\ApiResponse\Enums\ApiResponse;

/**
 * Inicio de sesión en backend
 * 
 * En determinados servicios se requiere autenticarse en el backend desde el
 * navegador.
 * 
 * @author Moisés Cortés C. <soy@mcortes.dev>
 * 
 * @version 1.0.0
 */
class AuthController extends Controller
{
    /**
     * Iniciar sesión
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->get('email'))->first();

        if (!$user || !$user->validateForPassportPasswordGrant($request->get('password'))) {
            return ApiResponse::UNPROCESSABLE_CONTENT->response([
                'email' => ['Usuario no valido']
            ]);
        }

        if (Auth::guard('web')->check()) {
            return ApiResponse::OK->response([
                "status" => 'logged'
            ]);
        } else {
            return ApiResponse::UNPROCESSABLE_CONTENT->response([
                "status" => Auth::guard('web')->login($user, true)
            ]);
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout()
    {
        Auth::guard('web')->logout();

        return ApiResponse::OK->response([
            "status" => 'logged out'
        ]);
    }
}
