<?php namespace App\Models;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de contraseñas olvidadas
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class ResetPassword extends Model
{
    /**
     * Atributos asignables
     */
    protected $fillable = [
        'user_id',
        'token',
        'created_at',
    ];

    /**
     * Desactivar fecha actualización
     */
    const UPDATED_AT = null;

    /**
     * Un reset de contraseña pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
