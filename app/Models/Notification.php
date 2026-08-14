<?php namespace App\Models;
/**
 * @copyright (c) 2026 MCortesDev (https://mcortes.dev) - All Rights Reserved
 */

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\HasCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotificationCollection;

/**
 * Notificaciones de usuario
 * 
 * @author Moisés Cortés C. <soy@mcortes.dev>
 * 
 * @version 1.0.0
 */
class Notification extends Model
{
    use HasCollection;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime'
    ];

    /**
     * 
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(fn($model) => $model->user_id = auth()?->user()?->id);
    }

    /**
     * The type of collection that should be used for the model.
     */
    protected static string $collectionClass = DatabaseNotificationCollection::class;

    /**
     * Get the notifiable entity that the notification belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo<\Illuminate\Database\Eloquent\Model, $this>
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Una notificación puede ser creada por un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Marcas notificación como leída
     *
     * @return void
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
    }

    /**
     * Marcar notificación como cerrada
     * 
     * La notificación no necesariaente fue vista, pero fue cerrada en el panel lateral.
     */
    public function markAsClosed()
    {
        $this->forceFill(['is_closed' => true])->save();
    }

    /**
     * Marcar notificación como no leída
     *
     * @return void
     */
    public function markAsUnread()
    {
        if (! is_null($this->read_at)) {
            $this->forceFill(['read_at' => null])->save();
        }
    }

    /**
     * Determinar si una notificación ha sido leída
     *
     * @return bool
     */
    public function read()
    {
        return $this->read_at !== null;
    }

    /**
     * Determinar si una notificación no ha sido leída
     *
     * @return bool
     */
    public function unread()
    {
        return $this->read_at === null;
    }

    /**
     * Filtrar notificaciones leídas
     *
     * @param  \Illuminate\Database\Eloquent\Builder<static>  $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopeRead(Builder $query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Filtrar notificaciones no leídas
     *
     * @param  \Illuminate\Database\Eloquent\Builder<static>  $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopeUnread(Builder $query)
    {
        return $query->whereNull('read_at');
    }
}
