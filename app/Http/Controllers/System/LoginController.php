<?php namespace App\Http\Controllers\System;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\User\ForgotRequest;
use App\Models\User;
use App\Notifications\ForgotPasswordNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Notsoweb\ApiResponse\Enums\ApiResponse;

/**
 * Controlador de sesiones
 * 
 * @author Moisés Cortés C <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class LoginController extends Controller
{
    /**
     * Iniciar sesión
     */
    public function login(LoginRequest $request)
    {
        return (Auth::attempt($request->all()))
            ? ApiResponse::OK->onSuccess([
                'user' => auth()->user(),
                'token' => auth()->user()
                    ->createToken('golscore')
                    ->accessToken,
            ])
            : ApiResponse::UNPROCESSABLE_CONTENT->response([
                'email' => ['Usuario no valido']
            ]);
    }

    /**
     * Cerrar sesión
     */
    public function logout()
    {
        return ApiResponse::OK->response([
            'is_revoked' => auth()->user()->token()->revoke()
        ]);
    }

    /**
     * Contraseña olvidada
     */
    public function forgotPassword(ForgotRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        try {
            $user->notify(new ForgotPasswordNotification());

            return ApiResponse::OK->response([
                'is_sent' => true
            ]);
        } catch (\Throwable $th) {
            Log::channel('mail')->info("Email: {$data['email']}");
            Log::channel('mail')->error($th->getMessage());

            return ApiResponse::INTERNAL_ERROR->response([
                'is_sent' => false,
            ]);
        }
    }
}
