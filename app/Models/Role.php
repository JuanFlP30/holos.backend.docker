<?php namespace App\Models;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */


use Notsoweb\LaravelCore\Traits\Models\Extended;
use Spatie\Permission\Models\Role as ModelsRole;

/**
 * Roles del sistema
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class Role extends ModelsRole
{
    use Extended;
}
