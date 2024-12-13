<?php namespace App\Schedules;
/**
 * @copyright (c) 2024 Notsoweb Software (https://www.notsoweb.com) - All rights reserved.
 */

use App\Models\ResetPassword;
use Illuminate\Support\Carbon;

/**
 * Eliminar tokens de reseteos de contraseñas
 * 
 * @author Moisés Cortés C <moises.cortes@notsoweb.com>
 */
class DeleteResetPasswords
{
    /**
     * Manipulador
     */
    public function __invoke()
    {
        ResetPassword::where('created_at', '<', Carbon::now()->subMinutes(10))->delete();
    }
}
