<?php namespace App\Http\Controllers\System;
/**
 * @copyright 2024 Notsoweb (https://notsoweb.com) - All rights reserved.
 */

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
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
        $q = request()->get('query');

        $model = auth()->user()
            ->notifications();

        if($q) {
            $model = $model->where('data->title', 'LIKE', "%{$q}%")
                ->orWhere('data->message', "LIKE", "%{$q}%");
        }
        
        return $this->view('index', [
            'models' => $model
                ->paginate(config('app.pagination'))
        ]);
    }

    /**
     * Todas las notificaciones
     */
    public function all(): JsonResponse
    {
        $query = request()->get('query');

        $model = auth()->user()
            ->notifications();

        if($query) {
            $model = $model->where('data->title', 'LIKE', "%{$query}%")
                ->orWhere('data->message', "LIKE", "%{$query}%");
        }
        
        return ApiResponse::OK->axios([
            'notifications' => $model
                ->paginate(config('app.pagination'))
        ]);
    }

    /**
     * Marcar notificación como leída
     */
    public function read(Request $request): JsonResponse
    {
        $notification = DatabaseNotification::find($request->get('id'));

        if ($notification) {
            $notification->markAsRead();
        }

        return ApiResponse::OK->axios();
    }

    /**
     * Obtener notificaciones no leídas recientes
     */
    public function allUnread(): JsonResponse
    {
        return ApiResponse::OK->axios([
            'total' => auth()->user()->unreadNotifications()->count(),
            'notifications' => auth()->user()->unreadNotifications()->limit(10)->get(),
        ]);
    }
}
