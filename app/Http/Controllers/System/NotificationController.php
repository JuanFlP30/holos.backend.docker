<?php namespace App\Http\Controllers\System;
/**
 * @copyright 2024 Notsoweb (https://notsoweb.com) - All rights reserved.
 */

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Notsoweb\ApiResponse\Enums\ApiResponse;
use Notsoweb\LaravelCore\Controllers\VueController;

/**
 * Sistema de notificaciones
 * 
 * @author Moisés de Jesús Cortés Castellanos <ing.moisesdejesuscortesc@notsoweb.com>
 * 
 * @version 1.0.0
 */
class NotificationController extends VueController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->root('notifications');
    }

    /**
     * Listar notificaciones del usuario
     */
    public function index()
    {
        $q = request()->get('q');

        $model = auth()->user()
            ->notifications();

        if($q) {
            $model = $model->where('data->title', 'LIKE', "%{$q}%")
                ->orWhere('data->message', "LIKE", "%{$q}%");
        }
        
        return ApiResponse::OK->response([
            'models' => $model
                ->paginate(config('app.pagination'))
        ]);
    }

    /**
     * Marcar notificación como leída
     */
    public function read(Request $request): JsonResponse
    {
        $notification = Notification::find($request->get('id'));

        if ($notification) {
            $notification->markAsRead();
        }

        return ApiResponse::OK->response();
    }

    /**
     * Marcar notificación como cerrada
     */
    public function close(Request $request): JsonResponse
    {
        $notification = Notification::find($request->get('id'));

        if ($notification) {
            $notification->markAsClosed();
        }

        return ApiResponse::OK->response();
    }

    /**
     * Obtener notificaciones no leídas recientes
     */
    public function allUnread(): JsonResponse
    {
        return ApiResponse::OK->response([
            'total' => auth()->user()->unreadNotifications()->count(),
            'unread_closed' => auth()->user()->unreadNotifications()->where('is_closed', true)->count(),
            'notifications' => auth()->user()->unreadNotifications()->where('is_closed', false)->limit(10)->get(),
        ]);
    }
}
