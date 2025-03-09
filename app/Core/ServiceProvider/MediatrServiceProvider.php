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
        $classMap = require base_path('vendor/composer/autoload_classmap.php');
    foreach ($classMap as $className => $filePath) {
        // On ne traite que les classes du namespace "App\Core\Features\"
        if (strpos($className, 'App\\Core\\Features\\') !== 0) {
            continue;
        }
        
        if (!class_exists($className)) {
            continue;
        }

        $reflection = new ReflectionClass($className);
        
        // Exemple de traitement pour les CommandHandler
        if ($reflection->implementsInterface(CommandHandlerInterface::class)) {
            $commandClass = $this->getCommandOrQueryClass($reflection, CommandInterface::class);
            if ($commandClass) {
                $mediator->registerCommandHandler($commandClass, $app->make($className));
            }
        }
        
        // Exemple de traitement pour les QueryHandler
        if ($reflection->implementsInterface(QueryHandlerInterface::class)) {
            $queryClass = $this->getCommandOrQueryClass($reflection, QueryInterface::class);
            if ($queryClass) {
                $mediator->registerQueryHandler($queryClass, $app->make($className));
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

    /**
 * Recherche dans la méthode handle() du handler le type de la commande (ou query) attendu(e).
 *
 * @param ReflectionClass $reflection
 * @param string $expectedInterface (CommandInterface ou QueryInterface)
 * @return string|null Le nom de la classe de la commande ou query, ou null si non trouvée
 */
/**
 * Recherche dans la méthode handle() du handler le type de la commande (ou query) attendu(e).
 *
 * Cette méthode essaie d'abord d'extraire le type depuis le docblock (via @param),
 * puis, en cas d'échec, utilise le type-hint du premier paramètre.
 *
 * @param ReflectionClass $reflection
 * @param string $expectedInterface (CommandInterface ou QueryInterface)
 * @return string|null Le nom de la classe de la commande ou query, ou null si non trouvée
 */
private function getCommandOrQueryClass(ReflectionClass $reflection, string $expectedInterface)
{
    if (!$reflection->hasMethod('handle')) {
        return null;
    }

    $handleMethod = $reflection->getMethod('handle');
    $docComment = $handleMethod->getDocComment();

    // Première approche : extraire le type depuis le docblock
    if ($docComment !== false) {
        // Recherche une annotation de type "@param CreateOrganizationCommand $command"
        if (preg_match('/@param\s+([\w\\\\]+)\s+\$[\w]+/', $docComment, $matches)) {
            $docType = $matches[1];
            // Si la classe n'existe pas, essayer de la résoudre avec le namespace du handler
            if (!class_exists($docType)) {
                $handlerNamespace = $reflection->getNamespaceName();
                $qualifiedDocType = $handlerNamespace . '\\' . $docType;
                if (class_exists($qualifiedDocType)) {
                    $docType = $qualifiedDocType;
                }
            }

            if (class_exists($docType)) {
                $refDocType = new ReflectionClass($docType);
                if ($refDocType->implementsInterface($expectedInterface)) {
                    return $docType;
                }
            }
        }
    }

    // Second approche : utiliser le type-hint du premier paramètre
    $parameters = $handleMethod->getParameters();
    if (empty($parameters)) {
        return null;
    }

    $firstParam = $parameters[0];
    $paramType = $firstParam->getType();
    if ($paramType !== null) {
        $typeName = $paramType->getName();
        if (class_exists($typeName)) {
            $refParamType = new ReflectionClass($typeName);
            if ($refParamType->implementsInterface($expectedInterface)) {
                return $typeName;
            }
        }
    }

    return null;
}

    public function boot()
    {
        // ...
    }
}
