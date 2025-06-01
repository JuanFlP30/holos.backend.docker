<?php namespace App\Models;
/**
 * @copyright (c) 2025 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * Eventos del usuario
 * 
 * Acciones que los usuarios realizan en el sistema.
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class UserEvent extends Model
{
    /**
     * Atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'event',
        'name',
        'data',
        'reportable_id',
        'reportable_type',
        'user_id'
    ];

    /**
     * Transformaciones
     */
    protected $casts = [
        'data' => 'json'
    ];

    /**
     * Atributos virtuales
     */
    protected $appends = [
        'description'
    ];

    /**
     * Desactivar fecha actualización
     */
    const UPDATED_AT = null;

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Descripción del evento
     */
    public function description() : Attribute
    {
        return Attribute::make(
            get: fn($value) => __($this->event, ['model' => $this->name]),
        );
    }

    /**
     * Relación con el modelo reportable
     */
    public function reportable()
    {
        return $this->morphTo();
    }

    /**
     * Reportar un evento
     */
    public static function report(Model $model, string $event, string $key = 'name', bool $reportChanges = false)
    {
        $event = strtolower(explode('\\', get_class($model))[2]) . '.' . $event;

        self::create([
            'event' => $event,
            'name' => $model->{$key},
            'data' => $reportChanges ? $model->getContrastChanges() : $model->fillableToArray(),
            'reportable_id' => $model->id,
            'reportable_type' => get_class($model),
            'user_id' => auth()->user()?->id
        ]);
    }
}
