<?php

namespace App\API\Attributes;

use ReflectionClass;
use ReflectionMethod;
use Illuminate\Http\Request;

class RequestBinder
{
    /**
     * Analyse la méthode du contrôleur et prépare les arguments à injecter.
     *
     * @param object  $controller L'instance du contrôleur
     * @param string  $method     La méthode à appeler
     * @param Request $request    La requête HTTP
     * @return array              Tableau associatif des arguments à injecter (nom paramètre => valeur)
     */
    public static function bindParameters(object $controller, string $method, Request $request): array
    {
        $reflectionMethod = new ReflectionMethod($controller, $method);
        $parameters       = $reflectionMethod->getParameters();
        $boundArguments   = [];

        // Décodage du corps de la requête en JSON
        $data = json_decode($request->getContent(), true) ?? [];

        foreach ($parameters as $parameter) {
            // Vérifier si le paramètre est annoté avec #[FromBody]
            $fromBodyAttributes = $parameter->getAttributes(FromBody::class);

            if (!empty($fromBodyAttributes)) {
                $paramType = $parameter->getType();

                if ($paramType && !$paramType->isBuiltin()) {
                    $className = $paramType->getName();

                    // Si la classe possède une méthode statique fromArray(), l'utiliser
                    if (method_exists($className, 'fromArray')) {
                        $boundArguments[$parameter->getName()] = $className::fromArray($data);
                    } else {
                        // Utilisation de la réflexion pour instancier en mappant les clés du tableau sur le constructeur
                        $reflectionClass = new ReflectionClass($className);
                        $constructor     = $reflectionClass->getConstructor();

                        if ($constructor) {
                            $constructorParams = $constructor->getParameters();
                            
                            // Si le constructeur attend un seul paramètre, on lui passe directement $data
                            if (count($constructorParams) === 1) {
                                $args = [$data];
                            } else {
                                $args = [];
                                foreach ($constructorParams as $consParam) {
                                    $argName = $consParam->getName();
                                    // Récupère la valeur correspondant au nom du paramètre ou null
                                    $args[] = $data[$argName] ?? null;
                                }
                            }
                            
                            $boundArguments[$parameter->getName()] = $reflectionClass->newInstanceArgs($args);
                        } else {
                            // Si la classe n'a pas de constructeur, on l'instancie directement
                            $boundArguments[$parameter->getName()] = new $className();
                        }
                    }
                } else {
                    // Si le type est primitif, on injecte directement le tableau de données
                    $boundArguments[$parameter->getName()] = $data;
                }
            } else {
                // Pour les autres paramètres, on vérifie s'ils attendent une instance de Request
                $paramType = $parameter->getType();
                if ($paramType && $paramType->getName() === Request::class) {
                    $boundArguments[$parameter->getName()] = $request;
                } else {
                    // Sinon, on peut choisir d'injecter null ou une autre valeur par défaut
                    $boundArguments[$parameter->getName()] = null;
                }
            }
        }
        return $boundArguments;
    }
}