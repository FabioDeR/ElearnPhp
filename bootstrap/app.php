<?php

use App\API\Middlewares\ExceptionHandlerMiddleware;
use App\API\Middlewares\FromBodyBindingMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        )
        ->withMiddleware(function (Middleware $middleware) {
        FromBodyBindingMiddleware::class;
        ExceptionHandlerMiddleware::class;        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
