<?php

namespace App\Core\ServiceProvider;

use App\Core\Features\Organizations\Commands\CreateOrganization\CreateOrganizationCommand;
use App\Core\Features\Organizations\Commands\CreateOrganization\CreateOrganizationHandler;
use App\Core\Features\Organizations\Queries\GetOrganization\GetOrganizationQuery;
use App\Core\Features\Organizations\Queries\GetOrganization\GetOrganizationQueryHandler;
use Illuminate\Support\ServiceProvider;
use App\Infrastructure\Mediatr\MediatorInterface;
use App\Infrastructure\Mediatr\SimpleMediator;

class MediatrServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(MediatorInterface::class, function ($app) {
            $mediator = new SimpleMediator();
            
            // Enregistrement des handlers de commandes (exemple déjà existant)
            $mediator->registerCommandHandler(CreateOrganizationCommand::class, $app->make(CreateOrganizationHandler::class));

            // Enregistrement du handler pour la query GetOrganizationQuery
            $mediator->registerQueryHandler(
                GetOrganizationQuery::class,
                $app->make(GetOrganizationQueryHandler::class)
            );
            
            return $mediator;
        });
    }

    public function boot()
    {
        // ...
    }
}