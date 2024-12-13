<?php namespace App\Http\Requests\User;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use Illuminate\Foundation\Http\FormRequest;

/**
 * Solicitud de olvido de contraseña
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class ForgotRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para realizar esta solicitud
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtener las reglas de validación que se aplican a la solicitud
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email']
        ];
    }
}
