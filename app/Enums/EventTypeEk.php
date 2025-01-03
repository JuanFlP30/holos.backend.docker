<?php namespace App\Enums;

use Notsoweb\LaravelCore\Traits\Enums\Extended;

/**
 * @copyright Copyright (c) 2001-2023 Golsystems (https://www.golsystems.mx) - All rights reserved.
 */

/**
 * Tipos de configuración
 * 
 * @author Moisés de Jesús Cortés Castellanos <ing.moisesdejesuscortesc@notsoweb.com>
 * 
 * @version 1.0.0
 */
enum EventTypeEk : string
{
    use Extended;

    /**
     * Texto
     */
    case STRING = 'S';

    /**
     * JSON
     */
    case JSON = 'J';

    /**
     * Booleano
     */
    case BOOL = 'B';

    /**
     * Entero
     */
    case INT = 'I';
}
