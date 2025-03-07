<?php

namespace App\Providers;

use App\Core\Contracts\Infrastucture\IAsyncRepository;
use App\Core\Contracts\Infrastucture\IOrganizationRepository;
use App\Domain\Models\Organization;
use App\Infrastructure\Persistence\Repositories\AsyncRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {        
        $this->app->bind(IAsyncRepository::class, AsyncRepository::class);
        $this->app->bind(IOrganizationRepository::class, Organization::class);
       
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        
    }
}
