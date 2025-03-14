<?php

namespace App\Providers;

use App\Core\Contracts\Infrastucture\IAsyncRepository;
use App\Core\Contracts\Infrastucture\IOrganizationRepository;
use App\Core\Contracts\Infrastucture\IUserRepository;
use App\Infrastructure\Persistence\Repositories\AsyncRepository;
use App\Infrastructure\Persistence\Repositories\OragnizationRepository;
use App\Infrastructure\Persistence\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {        
        $this->app->bind(IAsyncRepository::class, AsyncRepository::class);
        $this->app->bind(IOrganizationRepository::class, OragnizationRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);       
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        
    }
}
