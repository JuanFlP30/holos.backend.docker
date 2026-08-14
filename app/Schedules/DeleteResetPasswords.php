<?php namespace App\Schedules;
/**
 * @copyright (c) 2026 MCortesDev (https://mcortes.dev) - All rights reserved.
 */

use App\Models\ResetPassword;
use Illuminate\Support\Carbon;

/**
 * Eliminar tokens de reseteos de contraseñas
 * 
 * @author Moisés Cortés C <soy@mcortes.dev>
 */
class DeleteResetPasswords
{
    /**
     * Manipulador
     */
    public function __invoke()
    {
        ResetPassword::where('created_at', '<', Carbon::now()->subMinutes(15))->delete();
    }
}
