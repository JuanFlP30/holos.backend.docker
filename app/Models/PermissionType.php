<?php namespace App\Models;
/**
 * @copyright (c) 2026 MCortesDev (https://mcortes.dev) - All rights reserved.
 */

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

/**
 * Tipos de permisos
 * 
 * Permite agrupar permisos por tipo, por ejemplo: "Usuarios", "Roles", "Sistemas", etc.
 * 
 * @author Moisés Cortés C. <soy@mcortes.dev>
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

    /**
     * Un tipo de permiso tiene muchos permisos
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
