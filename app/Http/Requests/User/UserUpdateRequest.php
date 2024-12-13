<?php namespace App\Http\Requests\User;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Actualizar perfil usuario actual
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class UserUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'paternal' => ['required', 'string', 'max:255'],
            'maternal' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore(auth()->user()->id),
            ],
            'phone' => ['nullable', 'numeric', 'digits:10'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}
