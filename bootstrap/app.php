<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRoleRoutePermission;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ✅ Correct place to define middleware aliases
        $middleware->alias([
            'check.permission' => CheckRoleRoutePermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Define your exception handling if needed
    })
    ->create();
