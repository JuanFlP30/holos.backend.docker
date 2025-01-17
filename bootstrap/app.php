<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Notsoweb\ApiResponse\Enums\ApiResponse;
use Notsoweb\LaravelCore\Http\APIException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withBroadcasting(
        channels: __DIR__.'/../routes/channels.php',
        attributes: ['middleware' => ['auth:api']]
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('api', [
            StartSession::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'sanctum/csrf-cookie',
            'user/*'
        ]);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ServiceUnavailableHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return ApiResponse::SERVICE_UNAVAILABLE->response();
            }
        });
        $exceptions->render(APIException::notFound(...));
        $exceptions->render(APIException::unauthorized(...));
        $exceptions->render(APIException::unprocessableContent(...));
    })->create();
