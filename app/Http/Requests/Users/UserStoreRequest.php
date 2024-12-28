<?php namespace App\Http\Requests\Users;
/**
 * @copyright (C) 2024 Notsoweb Software (https://notsoweb.com) - All rights reserved
 */

use Illuminate\Foundation\Http\FormRequest;

/**
 * Almacenar usuario
 * 
 * @author Moisés Cortés C <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo('users.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'paternal' => ['required', 'string', 'max:255'],
            'maternal' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'numeric', 'digits:10'],
            'password' => ['required', 'string', 'min:8'],
            'roles' => ['nullable', 'array']
        ];
    }
}
