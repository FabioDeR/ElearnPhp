<?php

namespace App\Core\Features\Organizations\Queries\GetOrganizationById;

use App\Infrastructure\Mediatr\QueryInterface;

class GetByIdOrganizationQuery implements QueryInterface
{
    public string $id;

    public function __construct(string $id) {
        $this->id = $id;
    }
}