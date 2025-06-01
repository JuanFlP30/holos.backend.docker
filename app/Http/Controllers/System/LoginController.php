<?php namespace App\Http\Controllers\System;
/**
 * @copyright (c) 2025 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\User\ForgotRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Models\ResetPassword;
use App\Models\User;
use App\Notifications\ForgotPasswordNotification;
use Illuminate\Support\Facades\Log;
use Notsoweb\ApiResponse\Enums\ApiResponse;
use Ramsey\Uuid\Uuid;

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
        $user = User::where('email', $request->get('email'))->first();

        if (!$user || !$user->validateForPassportPasswordGrant($request->get('password'))) {
            return ApiResponse::UNPROCESSABLE_CONTENT->response([
                'email' => ['Usuario no valido']
            ]);
        }

        return ApiResponse::OK->onSuccess([
            'user' => $user,
            'token' => $user
                ->createToken('golscore')
                ->accessToken,
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
            $token = $this->generateToken($user);

            $user->notify(new ForgotPasswordNotification($token));

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

    /**
     * Resetear contraseña
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        
        $model = ResetPassword::with('user')->where('token', $data['token'])->first();

        if(!$model){
            return ApiResponse::UNPROCESSABLE_CONTENT->response([
                'token' => [__('auth.token.not_exists')]
            ]);
        }

        $expires = $model->created_at->addMinutes(15);

        if($expires < now()){
            $this->deleteToken($data['token']);

            return ApiResponse::UNPROCESSABLE_CONTENT->response([
                'token' => [__('auth.token.expired')]
            ]);
        }

        $model->user->update([
            'password' => bcrypt($data['password']),
        ]);

        $this->deleteToken($data['token']);

        return ApiResponse::OK->response([
            'is_updated' => true
        ]);
    }

    /**
     * Generar token
     */
    private function generateToken($user)
    {
        if($user->resetPasswords()->exists()){
            $user->resetPasswords()->delete();
        }

        $token = Uuid::uuid4()->toString();

        $user->resetPasswords()->create([
            'token' => $token,
        ]);

        return $token;
    }

    /**
     * Eliminar tokens
     */
    private function deleteToken($token)
    {
        ResetPassword::where('token', $token)->delete();
    }
}