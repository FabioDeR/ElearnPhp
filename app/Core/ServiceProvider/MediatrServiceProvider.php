<?php

namespace App\Core\ServiceProvider;

use Illuminate\Support\ServiceProvider;
use App\Infrastructure\Mediatr\MediatorInterface;
use App\Infrastructure\Mediatr\SimpleMediator;
use ReflectionClass;
use Illuminate\Support\Facades\File;
use App\Infrastructure\Mediatr\CommandHandlerInterface;
use App\Infrastructure\Mediatr\QueryHandlerInterface;
use App\Infrastructure\Mediatr\CommandInterface;
use App\Infrastructure\Mediatr\QueryInterface;
class MediatrServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(MediatorInterface::class, function ($app) {
            $mediator = new SimpleMediator();

            // 🔥 Auto-enregistrement des handlers dynamiquement
            $this->registerHandlers($mediator, $app);

            return $mediator;
        });
    }

    private function registerHandlers(SimpleMediator $mediator, $app)
    {
        // 📂 Définir les dossiers des handlers
        $paths = [
            app_path('Core/Features/Users/Commands'),
            app_path('Core/Features/Users/Queries'),
            app_path('Core/Features/Organizations/Commands'),
            app_path('Core/Features/Organizations/Queries'),
        ];

        foreach ($paths as $path) {
            if (!File::exists($path)) {
                continue; // S'assurer que le dossier existe
            }

            $files = File::allFiles($path);

            foreach ($files as $file) {
                $className = $this->getFullClassNameFromFile($file->getRealPath());

                if (!class_exists($className)) {
                    continue;
                }

                $reflection = new ReflectionClass($className);

                // 🔹 Vérifier si la classe implémente CommandHandlerInterface
                if ($reflection->implementsInterface(CommandHandlerInterface::class)) {
                    // Déterminer la Command associée
                    $commandClass = $this->getCommandOrQueryClass($reflection, CommandInterface::class);
                    if ($commandClass) {
                        $mediator->registerCommandHandler($commandClass, $app->make($className));
                    }
                }

                // 🔹 Vérifier si la classe implémente QueryHandlerInterface
                if ($reflection->implementsInterface(QueryHandlerInterface::class)) {
                    // Déterminer la Query associée
                    $queryClass = $this->getCommandOrQueryClass($reflection, QueryInterface::class);
                    if ($queryClass) {
                        $mediator->registerQueryHandler($queryClass, $app->make($className));
                    }
                }
            }
        }
    }

    private function getFullClassNameFromFile($filePath)
    {
        $content = file_get_contents($filePath);
        preg_match('/namespace\s+(.+?);/s', $content, $namespaceMatch);
        preg_match('/class\s+(\w+)/', $content, $classMatch);

        if ($namespaceMatch && $classMatch) {
            return $namespaceMatch[1] . '\\' . $classMatch[1];
        }

        return null;
    }

    private function getCommandOrQueryClass(ReflectionClass $reflection, string $expectedInterface)
    {
        $constructor = $reflection->getConstructor();
        if (!$constructor) {
            return null;
        }

        $parameters = $constructor->getParameters();
        if (count($parameters) === 0) {
            return null;
        }

        $firstParam = $parameters[0];
        $paramType = $firstParam->getType();
        
        if ($paramType && class_exists($paramType->getName())) {
            $paramReflection = new ReflectionClass($paramType->getName());
            if ($paramReflection->implementsInterface($expectedInterface)) {
                return $paramType->getName();
            }
        }

        return null;
    }

    public function boot()
    {
        // ...
    }
}