<?php namespace App\Events;
/**
 * @copyright (c) 2026 MCortesDev (https://mcortes.dev) - All Rights Reserved
 */

use App\Models\Role;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Role de usuario actualizado
 * 
 * Se envía cuando un usuario actualiza su rol, esto notifica al frontend que
 * debe de recargar los permisos del usuario.
 * 
 * @author Moisés Cortés C. <soy@mcortes.dev>
 * 
 * @version 1.0.0
 */
class UpdateRoleUser implements ShouldBroadcast
{
    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    /**
     * Crear instancia del evento
     */
    public function __construct(
        public Role $role
    ) {}

    /**
     * Obtener los canales a los que se debe transmitir el evento
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("App.Models.Role.{$this->role->id}"),
        ];
    }
}
