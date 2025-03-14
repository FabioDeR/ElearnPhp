<?php

namespace App\Core\Features\Organizations\Queries\GetAllOrganization;

use App\Core\Contracts\Infrastucture\IOrganizationRepository;
use App\Core\Features\Organizations\Queries\Dto\GetOrganizationDto;
use App\Infrastructure\Mediatr\QueryHandlerInterface;
use App\Infrastructure\Mediatr\QueryInterface;

class GetAllOrganizationHandler implements QueryHandlerInterface
{
    private IOrganizationRepository $repository;

    public function __construct(IOrganizationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param GetAllOrganizationQuery $query
     * @return array
     */
    public function handle(QueryInterface $query): array
    {
       // Récupérer toutes les organisations et transformer en DTO
    //    return $this->repository->listAll()
    //    ->map(fn($org) => new GetOrganizationDto(
    //        id: $org->id,
    //        name: $org->name,
    //        contact: $org->contact,
    //        full_address: $org->full_address
    //    ))->toArray(); 

        return [
            "message" => "Hello World"
        ];
    }
}