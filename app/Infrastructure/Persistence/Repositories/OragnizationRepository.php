<?php
namespace App\Infrastructure\Persistence\Repositories;

use App\Core\Contracts\Infrastucture\IOrganizationRepository;
use App\Domain\Models\Organization;

class OragnizationRepository extends AsyncRepository implements IOrganizationRepository
{
    public function __construct(Organization $model)
    {
        parent::__construct($model);
    }
}
