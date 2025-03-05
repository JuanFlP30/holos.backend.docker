<?php namespace App\Http\Controllers;

use Notsoweb\ApiResponse\Enums\ApiResponse;

/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

/**
 * Controlador de cambios del sistema
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class ChangelogController extends Controller
{
    /**
     * Cambios del sistema
     */
    public function __invoke()
    {
        return ApiResponse::OK->response(array_reverse([
            [
                'version' => '0.9.0',
                'date' => '2024-12-13',
                'changes' => [
                    'ADD: Personalización de stubs',
                    'ADD: Notificaciones en tiempo real básico.',
                    'ADD: Registro de usuarios',
                    'ADD: Sistema de autenticación',
                    'ADD: Instalación de Passport',
                    'INIT: Commit inicial',
                ]
            ],
            [
                'version' => '0.9.1',
                'date' => '2024-12-16',
                'changes' => [
                    'UPDATE: Variables de entorno',
                    'ADD: Logo de aplicación administrado en Backend.',
                ]
            ],
            [
                'version' => '0.9.2',
                'date' => '2024-12-16',
                'changes' => [
                    'UPDATE: Correcciones visuales en administración de usuarios.',
                ]
            ],
            [
                'version' => '0.9.3',
                'date' => '2024-12-27',
                'changes' => [
                    'ADD: Visualización de Usuarios conectados en tiempo real.',
                    'UPDATE: Notificaciones en tiempo real personalizadas.',
                ]
            ],
            [
                'version' => '0.9.4',
                'date' => '2024-12-28',
                'changes' => [
                    'REFACTOR: Refactorización de código y documentación.',
                ]
            ],
            [
                'version' => '0.9.5',
                'date' => '2025-01-03',
                'changes' => [
                    'ADD: Historial de acciones general.',
                    'ADD: Historial de acciones del los usuarios.',
                    'ADD: Monitorización de aplicación mediante Laravel Pulse.',
                ]
            ],
            [
                'version' => '0.9.6',
                'date' => '2025-01-06',
                'changes' => [
                    'ADD: Sistema de recuperación de contraseña.',
                ]
            ],
            [
                'version' => '0.9.7',
                'date' => '2025-01-16',
                'changes' => [
                    'ADD: Historial de cambios retornado en API.',
                    'UPDATE: Permitir el usuario de las sesiones para la API.',
                    'ADD: Comando para iniciar o detener todos los servicios',
                    'ADD: Observador de roles.',
                ]
            ],
            [
                'version' => '0.9.8',
                'date' => '2025-01-18',
                'changes' => [
                    'ADD: Obtener cualquier recurso mediante la URL /resources/get (requiere autenticación).',
                ]
            ],
            [
                'version' => '0.9.9',
                'date' => '2025-03-04',
                'changes' => [
                    'UPGRADE: Actualización de Laravel a 12.x.',
                    'UPGRADE: Versión minima de PHP a 8.3.',
                    'UPGRADE: Actualización general de dependencias.',
                ]
            ]
        ]));
    }
}