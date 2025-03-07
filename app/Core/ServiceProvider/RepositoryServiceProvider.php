<?php   
namespace App\Core\ServiceProvider;

use Illuminate\Support\ServiceProvider;
use App\Core\Contracts\Infrastucture\IAsyncRepository;
use App\Core\Contracts\Infrastucture\IOrganizationRepository;
use App\Infrastructure\Persistence\Repositories\AsyncRepository;
use App\Infrastructure\Persistence\Repositories\OragnizationRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IAsyncRepository::class, AsyncRepository::class);
        $this->app->bind(IOrganizationRepository::class, OragnizationRepository::class);
    }

    public function boot()
    {
        //
    }
}