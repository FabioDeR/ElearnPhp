<?php

namespace App\API\Middlewares;

use App\API\Exceptions\ApiResponse;
use Closure;
use Throwable;

class ExceptionHandlerMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (Throwable $e) {
            return ApiResponse::error(
                "Une erreur interne s'est produite",
                500,
                config('app.debug') ? $e->getMessage() : null
            );
        }
    }
}