<?php namespace App\Models;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Enums\SettingTypeEk;
use Illuminate\Database\Eloquent\Model;

/**
 * Configuraciones del sistema
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class Setting extends Model
{
    /**
     * Atributos permitidos
     */
    protected $fillable = [
        'key',
        'description',
        'value',
        'type_ek'
    ];

    /**
     * Transformación de los datos
     */
    protected $casts = [
        'value' => 'json'
    ];

    /**
     * Solicita o registra una configuración
     */
    public static function value(string $key, mixed $value = null, string $description = null, SettingTypeEk $type_ek = SettingTypeEk::STRING): mixed
    {
        $setting = self::where('key', $key)->first();

        if ($value !== null || $description !== null) {
            $toSave = [];

            if ($value !== null) {
                $toSave['value'] = $value;
            }

            if ($description !== null) {
                $toSave['description'] = $description;
            }

            if ($setting) {
                return $setting->update($toSave);
            } else {
                $toSave['key'] = $key;
                $toSave['type_ek'] = $type_ek;

                return self::create($toSave);
            }
        }

        return $setting?->value;
    }
}
