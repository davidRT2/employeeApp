<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'custom.sanctum' => \App\Http\Middleware\CustomSanctumAuthenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->renderable(function (AuthenticationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access. Please log in to continue.'
            ], 401);
        });
    })->create();
