<?php namespace App\Supports;
/**
 * @copyright Copyright (c) 2024 Notsoweb (https://notsoweb.com) - All rights reserved.
 */

/**
 * Facilita algunas consultas de búsqueda
 * 
 * @author Moisés de Jesús Cortés Castellanos <ing.moisesdejesuscortesc@notsoweb.com>
 * 
 * @version 1.0.0
 */
class QuerySupport
{
    /**
     * Realiza una búsqueda por medio de una clave
     */
    public static function queryByKey(&$model, $key = 'name')
    {
        $query = request()->get('q');

        if ($query) {
            $model = $model->where($key, $query);
        }
    }

    /**
     * Realiza una búsqueda por medio de una clave e incluye relaciones anidadas
     */
    public static function queryByKeys(&$model, array $keys = ['name'])
    {
        $query = request()->get('q');

        if ($query) {
            $model = $model->where(function ($x) use ($query, $keys) {
                $count = 0;

                foreach ($keys as $key) {
                    if (strpos($key, '.') !== false) {
                        // Si la clave contiene un punto, asumimos que es una relación anidada
                        list($relation, $relationKey) = explode('.', $key);

                        $x = ($count == 0)
                            ? $x->whereHas($relation, function ($q) use ($query, $relationKey) {
                                $q->where($relationKey, 'LIKE', "%{$query}%");
                            })
                            : $x->orWhereHas($relation, function ($q) use ($query, $relationKey) {
                                $q->where($relationKey, 'LIKE', "%{$query}%");
                            });
                    } else {
                        $x = ($count == 0)
                            ? $x->where($key, 'LIKE', "%{$query}%")
                            : $x->orWhere($key, 'LIKE', "%{$query}%");
                    }

                    $count++;
                }

                return $x;
            });
        }
    }

}