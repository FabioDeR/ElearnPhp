<?php

namespace App\API\Middlewares;

use App\API\Attributes\RequestBinder;
use Closure;
use Illuminate\Http\Request;
use ReflectionException;

class FromBodyBindingMiddleware
{
    /**
     * Intercepte la requête et appelle manuellement la méthode du contrôleur avec le binding personnalisé.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ReflectionException
     */
    public function handle(Request $request, Closure $next)
    {
        // Si la route n'est pas définie ou ne concerne pas un contrôleur, passer au middleware suivant
        if (!$request->route() || !$request->route()->getController()) {
            return $next($request);
        }

        $controller = $request->route()->getController();
        $method = $request->route()->getActionMethod();

        // Préparer les arguments à partir du corps de la requête
        $arguments = RequestBinder::bindParameters($controller, $method, $request);

        // Appeler manuellement la méthode du contrôleur avec les arguments liés
        return app()->call([$controller, $method], $arguments);
    }
}
