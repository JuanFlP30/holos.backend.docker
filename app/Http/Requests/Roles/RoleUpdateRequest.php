<?php namespace App\Http\Requests\Roles;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Actualizar rol
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class RoleUpdateRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para realizar esta solicitud
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo('roles.edit');
    }

    /**
     * Obtener las reglas de validación que se aplican a la solicitud
     */
    public function rules(): array
    {
        return [
            'description' => ['required', 'string', Rule::unique('roles')->ignore($this->route('role'))],
        ];
    }

    /**
     * Después de la validación
     */
    protected function passedValidation()
    {
        $this->merge([
            'name' => Str::slug($this->description),
        ]);
    }
}
