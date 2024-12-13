<?php namespace App\Models;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All rights reserved.
 */

use Illuminate\Database\Eloquent\Model;

/**
 * Tipos de permisos
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class PermissionType extends Model
{
    /**
     * Atributos permitidos
     */
    protected $fillable = [
        'name',
        'description'
    ];
}
