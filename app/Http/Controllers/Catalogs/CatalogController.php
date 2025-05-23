<?php namespace App\Http\Controllers\Catalogs;
/**
 * @copyright (c) 2025 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notsoweb\ApiResponse\Enums\ApiResponse;

/**
 * Catálogos de la aplicación
 * 
 * Permite obtener múltiples catálogos de la aplicación en una sola petición, estas son clases que se encuentran dinámicamente
 * en el directorio app/Http/Controllers/Catalogs. 
 * 
 * Estas clases solo retornan los datos de una consulta y este controlador los retorna en una solo respuesta.
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class CatalogController extends Controller
{
    /**
     * Obtener cualquier recurso
     * 
     * Procesa la matriz de recursos solicitados y retorna una respuesta con los datos de cada recurso.
     */
    public function get(Request $request)
    {
        $resources = $request->all();
        $response = [];

        foreach ($resources as $resource => $data) {
            $exploded = explode(':', $resource);

            try {
                $class = "App\Http\Controllers\Catalogs\\{$this->toPascalCase($exploded[0])}Resource";
                $method = $this->toCamelCase($exploded[1]);

                $response[$resource] = (new $class)->{$method}($data);
            } catch (\Exception $e) {
                $response[$resource] = false;
            }
        }

        return ApiResponse::OK->onSuccess($response);
    }

    /**
     * Transforma un string de formato kebab-case o snake_case a PascalCase
     * 
     * @param string $string String a transformar
     * @return string String en formato PascalCase
     */
    private function toPascalCase(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
    }

    /**
     * Transforma un string de formato kebab-case o snake_case a camelCase
     * 
     * @param string $string String a transformar
     * @return string String en formato camelCase
     */
    private function toCamelCase(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string))));
    }
}