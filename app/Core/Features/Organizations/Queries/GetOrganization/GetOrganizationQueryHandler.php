<?php
namespace App\Core\Features\Organizations\Queries\GetOrganization;

use App\Core\Contracts\Infrastucture\IOrganizationRepository;
use App\Infrastructure\Mediatr\QueryHandlerInterface;
use App\Infrastructure\Mediatr\QueryInterface;
use Illuminate\Support\Facades\Cache;

class GetOrganizationQueryHandler implements QueryHandlerInterface {

    private IOrganizationRepository $OrganizationRepository;

    public function __construct(IOrganizationRepository $OrganizationRepository)
    {
        $this->OrganizationRepository = $OrganizationRepository;
    }


    public function handle(QueryInterface $query) {
        
        return Cache::remember("organization_{$query->id}", 3600, function () use ($query) {
            $organization = $this->OrganizationRepository->getById($query->id ?? null);
    
            if (!$organization) {
                return null;
            }
    
            return new GetOrganizationDto(
                $organization->id,
                $organization->nom,
                $organization->contact,
                $organization->adresse_complete
            );
        });
    }
}