<?php

namespace App\Core\Features\Organizations\Queries\GetOrganizationById;

use App\Core\Contracts\Infrastucture\IOrganizationRepository;
use App\Infrastructure\Mediatr\QueryHandlerInterface;
use App\Infrastructure\Mediatr\QueryInterface;

class GetByIdOrganizationHandler implements QueryHandlerInterface
{
    private IOrganizationRepository $repository;

    public function __construct(IOrganizationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param GetByIdOrganizationQuery $query
     * @return object
     */
    public function handle(QueryInterface $query)
    {
        return $this->repository->getById($query->id);
    }
}