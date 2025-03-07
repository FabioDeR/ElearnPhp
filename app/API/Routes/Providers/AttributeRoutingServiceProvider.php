<?php

namespace App\API\Routes\Providers;

use App\API\Attributes\HttpGet;
use App\API\Attributes\HttpPost;
use App\API\Middlewares\FromBodyBindingMiddleware;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class AttributeRoutingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $controllerPath = app_path('API/Controllers');
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($controllerPath));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $className = $this->getClassFullNameFromFile($file->getRealPath());

                if (! $className || ! class_exists($className)) {
                    continue;
                }

                $refClass = new ReflectionClass($className);
                $controllerName = $this->getControllerName($refClass);
                $prefix = 'api/' . strtolower($controllerName);

                foreach ($refClass->getMethods() as $method) {
                    foreach ($method->getAttributes() as $attribute) {
                        $instance = $attribute->newInstance();
                        $routeUri = rtrim($prefix, '/') . '/' . ltrim($instance->uri, '/');

                        // Gérer les différentes méthodes HTTP
                        match (true) {
                            $instance instanceof HttpGet => Route::get($routeUri, [$className, $method->getName()]),
                            $instance instanceof HttpPost => Route::post($routeUri, [$className, $method->getName()])->middleware(FromBodyBindingMiddleware::class),                         
                        };
                    }
                }
            }
        }
    }

    protected function getControllerName(ReflectionClass $refClass): string
    {
        $controllerName = $refClass->getShortName();
        return str_ends_with($controllerName, 'Controller') ? substr($controllerName, 0, -strlen('Controller')) : $controllerName;
    }

    protected function getClassFullNameFromFile($filePath): ?string
    {
        $contents  = file_get_contents($filePath);
        $namespace = null;
        $class     = null;
        $tokens    = token_get_all($contents);
        $count     = count($tokens);

        for ($i = 0; $i < $count; $i++) {
            if ($tokens[$i][0] === T_NAMESPACE) {
                $namespace = '';
                for ($j = $i + 1; $j < $count; $j++) {
                    if ($tokens[$j][0] === T_STRING || $tokens[$j][0] === T_NAME_QUALIFIED) {
                        $namespace .= $tokens[$j][1];
                    } elseif ($tokens[$j] === ';' || $tokens[$j] === '{') {
                        break;
                    }
                }
            }
            if ($tokens[$i][0] === T_CLASS) {
                for ($j = $i + 1; $j < $count; $j++) {
                    if ($tokens[$j] === '{') {
                        $class = $tokens[$i + 2][1];
                        break 2;
                    }
                }
            }
        }

        return $namespace && $class ? $namespace . '\\' . $class : $class;
    }
}
