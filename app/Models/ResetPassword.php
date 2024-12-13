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
        'email',
        'token',
        'created_at',
    ];
}
