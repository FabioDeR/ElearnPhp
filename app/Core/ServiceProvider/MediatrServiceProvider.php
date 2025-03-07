<?php

namespace App\Core\ServiceProvider;

use App\Core\Features\Organizations\Commands\CreateOrganization\CreateOrganizationCommand;
use App\Core\Features\Organizations\Commands\CreateOrganization\CreateOrganizationHandler;
use Illuminate\Support\ServiceProvider;
use App\Infrastructure\Mediatr\MediatorInterface;
use App\Infrastructure\Mediatr\SimpleMediator;

class MediatrServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(MediatorInterface::class, function ($app) {
            $mediator = new SimpleMediator();
            
            // Enregistrer les handlers pour chaque commande
            $mediator->registerHandler(
                CreateOrganizationCommand::class,
                $app->make(CreateOrganizationHandler::class)
            );

            // Vous pouvez enregistrer d'autres handlers ici

            return $mediator;
        });
    }

    public function boot()
    {
        // ...
    }
}