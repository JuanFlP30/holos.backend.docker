<?php namespace App\Models;
/**
 * @copyright (c) 2026 MCortesDev (https://mcortes.dev) - All Rights Reserved
 */

use App\Observers\RoleObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Notsoweb\LaravelCore\Traits\Models\Extended;
use Spatie\Permission\Models\Role as ModelsRole;

/**
 * Roles del sistema
 * 
 * Los roles son grupos de permisos que pueden ser asignados a usuarios.
 * 
 * @author Moisés Cortés C. <soy@mcortes.dev>
 * 
 * @version 1.0.0
 */
#[ObservedBy([RoleObserver::class])]
class Role extends ModelsRole
{
    use Extended;
}
